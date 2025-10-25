<?php

declare(strict_types=1);

namespace Tests\Feature\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class MigrateFromMysqlTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_exists(): void
    {
        $this->assertTrue(class_exists(\App\Console\Commands\MigrateFromMysql::class));
    }

    public function test_command_can_run_in_dry_run_mode(): void
    {
        $exitCode = Artisan::call('migrate:from-mysql', [
            '--dry-run' => true,
            '--table' => ['languages'],
        ]);

        $this->assertEquals(0, $exitCode);
    }

    public function test_command_displays_help(): void
    {
        $exitCode = Artisan::call('migrate:from-mysql', ['--help' => true]);

        $this->assertEquals(0, $exitCode);
    }
}
