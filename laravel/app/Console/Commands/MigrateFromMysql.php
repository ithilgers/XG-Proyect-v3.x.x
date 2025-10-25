<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateFromMysql extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'migrate:from-mysql
                            {--table=* : Specific tables to migrate}
                            {--chunk=1000 : Chunk size for batch processing}
                            {--dry-run : Run in dry-run mode without actual migration}';

    /**
     * The console command description.
     */
    protected $description = 'Migrate data from MySQL to PostgreSQL';

    /**
     * Tables to migrate in order (respecting foreign key dependencies)
     */
    private array $tables = [
        // Core tables (no dependencies)
        'languages',
        'options',

        // User tables
        'users',
        'users_statistics',
        'sessions',

        // Alliance tables
        'alliances',
        'alliance_statistics',

        // Planet tables
        'planets',
        'buildings',
        'ships',
        'defenses',

        // Research
        'research',

        // Fleet and combat
        'fleets',
        'reports',
        'acs',

        // Social
        'buddys',
        'messages',
        'notes',

        // Admin
        'banned',
        'changelog',
        'premium',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║        MySQL to PostgreSQL Data Migration Tool            ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->newLine();

        // Verify connections
        if (!$this->verifyConnections()) {
            return 1;
        }

        $selectedTables = $this->option('table') ?: $this->tables;
        $chunkSize = (int) $this->option('chunk');
        $dryRun = (bool) $this->option('dry-run');

        if ($dryRun) {
            $this->warn('🔍 DRY-RUN MODE: No data will be migrated');
            $this->newLine();
        }

        $this->info('Migration Configuration:');
        $this->table(
            ['Setting', 'Value'],
            [
                ['Tables', count($selectedTables)],
                ['Chunk Size', $chunkSize],
                ['Dry Run', $dryRun ? 'Yes' : 'No'],
            ]
        );
        $this->newLine();

        // Confirm before proceeding
        if (!$dryRun && !$this->confirm('Do you want to proceed with the migration?')) {
            $this->warn('Migration cancelled.');

            return 0;
        }

        // Migrate tables
        $startTime = now();
        $totalRecords = 0;

        foreach ($selectedTables as $table) {
            $records = $this->migrateTable($table, $chunkSize, $dryRun);
            $totalRecords += $records;
        }

        $duration = $startTime->diffForHumans(null, true);

        $this->newLine();
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info("║ Migration {$this->pad($dryRun ? 'Summary (DRY-RUN)' : 'Completed', 46)}║");
        $this->info('╠════════════════════════════════════════════════════════════╣');
        $this->info("║ Total Records: {$this->pad(number_format($totalRecords), 43)}║");
        $this->info("║ Duration: {$this->pad($duration, 48)}║");
        $this->info('╚════════════════════════════════════════════════════════════╝');

        return 0;
    }

    /**
     * Verify database connections
     */
    private function verifyConnections(): bool
    {
        $this->info('Verifying database connections...');

        // Check MySQL connection
        try {
            DB::connection('mysql')->getPdo();
            $this->line('✓ MySQL connection: OK');
        } catch (\Exception $e) {
            $this->error('✗ MySQL connection failed: '.$e->getMessage());

            return false;
        }

        // Check PostgreSQL connection
        try {
            DB::connection('pgsql')->getPdo();
            $this->line('✓ PostgreSQL connection: OK');
        } catch (\Exception $e) {
            $this->error('✗ PostgreSQL connection failed: '.$e->getMessage());

            return false;
        }

        $this->newLine();

        return true;
    }

    /**
     * Migrate a single table
     */
    private function migrateTable(string $table, int $chunkSize, bool $dryRun): int
    {
        $prefix = config('database.connections.pgsql.prefix', 'xgp_');
        $fullTableName = $prefix.$table;

        $this->line("→ Migrating table: <fg=cyan>{$table}</>");

        // Check if table exists in MySQL
        if (!Schema::connection('mysql')->hasTable($fullTableName)) {
            $this->warn("  ⚠ Table {$fullTableName} does not exist in MySQL. Skipping.");

            return 0;
        }

        // Check if table exists in PostgreSQL
        if (!Schema::connection('pgsql')->hasTable($fullTableName)) {
            $this->warn("  ⚠ Table {$fullTableName} does not exist in PostgreSQL. Skipping.");

            return 0;
        }

        // Get total record count
        $totalRecords = DB::connection('mysql')->table($fullTableName)->count();

        if ($totalRecords === 0) {
            $this->line('  ℹ No records to migrate');

            return 0;
        }

        if ($dryRun) {
            $this->line("  ℹ Would migrate {$totalRecords} records");

            return $totalRecords;
        }

        // Truncate PostgreSQL table before migration
        $this->line('  ℹ Truncating PostgreSQL table...');
        DB::connection('pgsql')->table($fullTableName)->truncate();

        // Create progress bar
        $bar = $this->output->createProgressBar($totalRecords);
        $bar->setFormat('  [%bar%] %percent:3s%% (%current%/%max%) %elapsed:6s% / %estimated:-6s%');

        $migratedRecords = 0;

        // Get primary key column
        $primaryKey = $this->getPrimaryKey($table);

        // Migrate in chunks
        DB::connection('mysql')
            ->table($fullTableName)
            ->orderBy($primaryKey)
            ->chunk($chunkSize, function ($records) use ($fullTableName, $bar, &$migratedRecords) {
                $data = $records->map(function ($record) use ($fullTableName) {
                    return $this->transformRecord($fullTableName, (array) $record);
                })->toArray();

                // Insert into PostgreSQL
                DB::connection('pgsql')->table($fullTableName)->insert($data);

                $migratedRecords += count($records);
                $bar->advance(count($records));
            });

        $bar->finish();
        $this->newLine();

        // Reset sequence for auto-increment columns
        $this->resetSequence($fullTableName, $primaryKey);

        $this->line("  <fg=green>✓ Migrated {$migratedRecords} records</>");
        $this->newLine();

        return $migratedRecords;
    }

    /**
     * Transform record for PostgreSQL compatibility
     */
    private function transformRecord(string $table, array $record): array
    {
        // Convert JSONB fields
        $jsonbFields = $this->getJsonbFields($table);

        foreach ($jsonbFields as $field) {
            if (isset($record[$field]) && is_string($record[$field])) {
                // Try to decode if it's serialized PHP or JSON
                $decoded = @unserialize($record[$field]);
                if ($decoded === false) {
                    $decoded = @json_decode($record[$field], true);
                }

                $record[$field] = $decoded ? json_encode($decoded) : null;
            }
        }

        // Convert boolean fields for PostgreSQL
        foreach ($record as $key => $value) {
            if (is_bool($value)) {
                $record[$key] = $value ? 1 : 0;
            }
        }

        return $record;
    }

    /**
     * Get JSONB fields for a table
     */
    private function getJsonbFields(string $table): array
    {
        $prefix = config('database.connections.pgsql.prefix', 'xgp_');
        $cleanTable = str_replace($prefix, '', $table);

        $jsonbMap = [
            'planets' => ['planet_fields', 'planet_debris', 'planet_production'],
            'buildings' => ['building_queue'],
            'ships' => ['ship_queue'],
            'defenses' => ['defense_queue'],
            'research' => ['research_queue'],
            'fleets' => ['fleet_composition'],
            'reports' => ['report_data'],
            'acs' => ['acs_members', 'acs_invited'],
        ];

        return $jsonbMap[$cleanTable] ?? [];
    }

    /**
     * Get primary key column name for a table
     */
    private function getPrimaryKey(string $table): string
    {
        $primaryKeyMap = [
            'users' => 'user_id',
            'users_statistics' => 'stat_id',
            'sessions' => 'session_id',
            'alliances' => 'alliance_id',
            'alliance_statistics' => 'stat_id',
            'planets' => 'planet_id',
            'buildings' => 'building_id',
            'ships' => 'ship_id',
            'defenses' => 'defense_id',
            'research' => 'research_id',
            'fleets' => 'fleet_id',
            'reports' => 'report_id',
            'acs' => 'acs_id',
            'buddys' => 'buddy_id',
            'messages' => 'message_id',
            'notes' => 'note_id',
            'banned' => 'banned_id',
            'changelog' => 'changelog_id',
            'languages' => 'language_id',
            'options' => 'option_id',
            'premium' => 'premium_id',
        ];

        return $primaryKeyMap[$table] ?? 'id';
    }

    /**
     * Reset PostgreSQL sequence for auto-increment
     */
    private function resetSequence(string $table, string $primaryKey): void
    {
        $maxId = DB::connection('pgsql')->table($table)->max($primaryKey);

        if ($maxId) {
            $sequenceName = "{$table}_{$primaryKey}_seq";
            DB::connection('pgsql')->statement(
                "SELECT setval('{$sequenceName}', {$maxId})"
            );
        }
    }

    /**
     * Pad string for table display
     */
    private function pad(string $text, int $length): string
    {
        return str_pad($text, $length);
    }
}
