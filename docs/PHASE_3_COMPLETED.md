# Phase 3: Eloquent Models & Data Migration - COMPLETED ✅

## Overview

Phase 3 focused on creating comprehensive Eloquent models for all database tables, implementing the data migration system from MySQL to PostgreSQL, and establishing a solid testing foundation.

**Completion Date:** 2025-10-25
**Duration:** Completed in single session
**Status:** ✅ All models, migrations, and tests created

---

## Deliverables

### ✅ Eloquent Models Created (21 models)

All model files created in `laravel/app/Models/`:

#### Core Models (Previously Existed)
1. **BaseModel.php** - Abstract base model with common functionality
2. **User.php** - User authentication and account management
3. **Planet.php** - Planet management with JSONB support
4. **Alliance.php** - Alliance system

#### Game Mechanics Models (NEW)
5. **Building.php** - Planet buildings with JSONB queue
6. **Ship.php** - Spaceship management with JSONB queue
7. **Defense.php** - Defense structures with JSONB queue
8. **Research.php** - User research with JSONB queue
9. **Fleet.php** - Fleet movements with JSONB composition

#### Reports & Communication Models (NEW)
10. **Report.php** - Battle/espionage reports with JSONB data
11. **Message.php** - In-game messaging with full-text search
12. **Note.php** - User notes with full-text search

#### Social Models (NEW)
13. **Buddy.php** - Friend system

#### Alliance Combat System (NEW)
14. **Acs.php** - Alliance Combat System with JSONB members

#### Statistics Models (NEW)
15. **UserStatistic.php** - User rankings and statistics
16. **AllianceStatistic.php** - Alliance rankings and statistics

#### Admin & System Models (NEW)
17. **Session.php** - User session management
18. **BannedUser.php** - Ban management system
19. **Changelog.php** - Game changelog with full-text search
20. **Language.php** - Multi-language support
21. **Option.php** - Game configuration
22. **Premium.php** - Premium features tracking

---

## Model Features Implemented

### 1. Relationships

All models have proper relationships defined:

```php
// User relationships
$user->planets()           // HasMany
$user->homePlanet()        // BelongsTo
$user->research()          // HasOne
$user->alliance()          // BelongsTo
$user->fleets()            // HasMany
$user->statistics()        // HasOne

// Planet relationships
$planet->user()            // BelongsTo
$planet->buildings()       // HasOne
$planet->ships()           // HasOne
$planet->defenses()        // HasOne
$planet->fleets()          // HasMany

// Fleet relationships
$fleet->owner()            // BelongsTo
$fleet->startPlanet()      // BelongsTo
$fleet->endPlanet()        // BelongsTo
$fleet->targetUser()       // BelongsTo
```

### 2. PostgreSQL JSONB Casting

Models utilize PostgreSQL JSONB for flexible data:

```php
// Building model
protected $casts = [
    'building_queue' => 'array', // JSONB in PostgreSQL
];

// Fleet model
protected $casts = [
    'fleet_composition' => 'array', // Ships in fleet
];

// Report model
protected $casts = [
    'report_data' => 'array', // Complete battle report
];

// Planet model (from Phase 1)
protected $casts = [
    'planet_fields' => 'array',
    'planet_debris' => 'array',
    'planet_production' => 'array',
];
```

### 3. Custom Scopes

Powerful query scopes for common operations:

```php
// Fleet scopes
Fleet::active()->get()                      // Active fleets only
Fleet::byMission(3)->get()                  // By mission type
Fleet::targeting(1, 100, 5)->get()         // Targeting coordinates

// Report scopes
Report::unread()->get()                     // Unread reports
Report::byType('combat')->get()            // By report type
Report::recent(7)->get()                   // Last 7 days

// Message scopes
Message::inbox($userId)->get()             // User inbox
Message::sent($userId)->get()              // Sent messages
Message::search('attack')->get()           // Full-text search

// User Statistics scopes
UserStatistic::topPlayers(100)->get()      // Top 100 players
UserStatistic::minimumPoints(10000)->get() // Min points filter
```

### 4. Business Logic Methods

Models contain game-specific logic:

```php
// Building methods
$building->getBuildingLevel(1)             // Get level by ID
$building->isBuildingInQueue(4)            // Check if in queue
$building->getQueueCount()                 // Get queue size

// Ship methods
$ship->getShipCount(202)                   // Get ship count by ID
$ship->getTotalShips()                     // Total ship count
$ship->decreaseShip(204, 10)              // Decrease ship count
$ship->increaseShip(204, 5)               // Increase ship count

// Research methods
$research->getResearchLevel(106)           // Get research level
$research->hasActiveResearch()             // Check if researching
$research->getMaxPlanets()                 // Max planets (astrophysics)
$research->getMaxExpeditions()             // Max expeditions

// Fleet methods
$fleet->hasArrived()                       // Check if arrived
$fleet->isReturning()                      // Check if returning
$fleet->getShipCount(202)                  // Ships in fleet

// Report methods
$report->markAsRead()                      // Mark as read
$report->isCombatReport()                  // Check type
$report->isEspionageReport()              // Check type

// User methods (from Phase 1)
$user->isBanned()                          // Check ban status
$user->hasPremium()                        // Check premium
$user->isOnVacation()                      // Check vacation mode
```

### 5. Accessor Attributes

Convenient computed attributes:

```php
// Fleet
$fleet->startCoordinates                   // "1:100:5"
$fleet->endCoordinates                     // "2:200:10"

// Report
$report->coordinates                       // "1:100:5"

// ACS
$acs->targetCoordinates                    // "3:150:8"
$acs->memberCount                          // Number of members

// User Statistics
$userStats->totalRankChange                // Rank improvement

// Alliance Statistics
$allianceStats->averagePoints              // Avg points per member

// Premium
$premium->remainingDays                    // Days remaining
```

---

## Data Migration System

### ✅ Migration Command Created

**File:** `laravel/app/Console/Commands/MigrateFromMysql.php`

Comprehensive data migration tool with features:
- ✅ Table-by-table migration with progress bars
- ✅ Chunk processing for memory efficiency
- ✅ Dry-run mode for testing
- ✅ Automatic JSONB transformation
- ✅ Foreign key dependency ordering
- ✅ Sequence reset for auto-increment columns
- ✅ Connection verification
- ✅ Error handling and reporting

### Usage

```bash
# Migrate all tables
php artisan migrate:from-mysql

# Specific tables only
php artisan migrate:from-mysql --table=users --table=planets

# Custom chunk size (default 1000)
php artisan migrate:from-mysql --chunk=5000

# Dry-run mode (test without migrating)
php artisan migrate:from-mysql --dry-run

# Help
php artisan migrate:from-mysql --help
```

### Migration Order

Tables are migrated in dependency order to respect foreign keys:

1. **Core tables**: languages, options
2. **User tables**: users, users_statistics, sessions
3. **Alliance tables**: alliances, alliance_statistics
4. **Planet tables**: planets, buildings, ships, defenses
5. **Research**: research
6. **Fleet & Combat**: fleets, reports, acs
7. **Social**: buddys, messages, notes
8. **Admin**: banned, changelog, premium

### Data Transformation

The migration automatically handles:

**JSONB Conversion:**
```php
// Serialized PHP arrays → JSON
$phpArray = unserialize($mysqlData);
$jsonData = json_encode($phpArray);

// MySQL JSON → PostgreSQL JSONB
// Automatic conversion with proper indexing
```

**Boolean Conversion:**
```php
// MySQL TINYINT → PostgreSQL BOOLEAN
true → 1
false → 0
```

**Sequence Reset:**
```php
// Reset auto-increment sequences
SELECT setval('xgp_users_user_id_seq', MAX(user_id));
```

---

## Model Factories

### ✅ Factory Files Created (8 factories)

All factories in `laravel/database/factories/`:

1. **BuildingFactory.php** - Generate test buildings
2. **ShipFactory.php** - Generate test ships
3. **DefenseFactory.php** - Generate test defenses
4. **ResearchFactory.php** - Generate test research
5. **FleetFactory.php** - Generate test fleets
6. **ReportFactory.php** - Generate test reports
7. **MessageFactory.php** - Generate test messages
8. **UserStatisticFactory.php** - Generate test statistics

### Factory Features

**State Methods:**
```php
// Buildings with queue
Building::factory()->withQueue()->create();

// Ships with queue
Ship::factory()->withQueue()->create();

// Research with active research
Research::factory()->withActiveResearch()->create();

// Returning fleet
Fleet::factory()->returning()->create();

// Arrived fleet
Fleet::factory()->arrived()->create();

// Unread reports
Report::factory()->unread()->create();

// Combat report
Report::factory()->combat()->create();

// Top player statistics
UserStatistic::factory()->topPlayer()->create();
```

**Usage Examples:**
```php
// Create user with planets and buildings
$user = User::factory()
    ->has(Planet::factory()->count(3)
        ->has(Building::factory())
        ->has(Ship::factory())
        ->has(Defense::factory())
    )
    ->has(Research::factory())
    ->has(UserStatistic::factory())
    ->create();

// Create fleet between planets
$fleet = Fleet::factory()->create([
    'fleet_owner' => $user->user_id,
    'fleet_composition' => [
        '202' => 100, // Small Cargo
        '204' => 50,  // Light Fighter
    ],
]);
```

---

## Testing Suite

### ✅ Feature Tests Created (4 test files)

All tests in `laravel/tests/Feature/`:

1. **Models/BuildingTest.php** - Building model tests
2. **Models/FleetTest.php** - Fleet model tests
3. **Models/ResearchTest.php** - Research model tests
4. **Commands/MigrateFromMysqlTest.php** - Migration command tests

### Test Coverage

**Building Tests:**
- ✅ Relationship to planet
- ✅ Get building level by ID
- ✅ Detect buildings in queue
- ✅ Get queue count
- ✅ JSONB storage verification

**Fleet Tests:**
- ✅ Relationship to owner
- ✅ Coordinate attributes
- ✅ Arrival detection
- ✅ Return status
- ✅ Ship count from composition
- ✅ Active fleet scope
- ✅ JSONB composition storage

**Research Tests:**
- ✅ Relationship to user
- ✅ Get research level by ID
- ✅ Active research detection
- ✅ Max planets calculation
- ✅ Max expeditions calculation

**Migration Command Tests:**
- ✅ Command existence
- ✅ Dry-run mode execution
- ✅ Help display

### Running Tests

```bash
# All tests
composer phpunit

# Specific suite
php artisan test --testsuite=Feature

# Specific test file
php artisan test --filter=FleetTest

# With coverage
php artisan test --coverage
```

---

## Model Statistics

| Category | Count |
|----------|-------|
| **Total Models** | 22 |
| **Models with JSONB** | 9 |
| **Models with Relationships** | 18 |
| **Models with Scopes** | 12 |
| **Models with Business Logic** | 15 |
| **Model Factories** | 8 |
| **Feature Tests** | 4 |
| **Total Test Cases** | 20+ |

---

## Technical Highlights

### 1. PHP 8.3 Features

All models use modern PHP features:

```php
declare(strict_types=1);

namespace App\Models;

class Fleet extends BaseModel
{
    // Typed properties (ready for implementation)
    protected $table = 'fleets';

    // Array casting for JSONB
    protected $casts = [
        'fleet_composition' => 'array',
    ];

    // Typed method returns
    public function hasArrived(): bool
    {
        return now()->greaterThan($this->fleet_end_time);
    }
}
```

### 2. PostgreSQL-Specific Features

**JSONB Queries:**
```php
// Query within JSONB
Fleet::whereRaw("fleet_composition ? '204'")->get();

// JSONB value comparison
Report::whereRaw("report_data->>'winner' = 'attacker'")->get();
```

**Full-Text Search:**
```php
// Message search
Message::search('alliance war')->get();

// Note search
Note::search('strategy')->get();

// Changelog search
Changelog::search('bugfix')->get();
```

### 3. Model Inheritance

All models extend `BaseModel`:

```php
abstract class BaseModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
```

Benefits:
- Consistent behavior across all models
- Automatic factory support
- Soft deletes enabled
- Timestamp casting

---

## Next Steps (Phase 4)

### 1. Run Migrations

```bash
# Start PostgreSQL
docker-compose -f docker-compose.dev.yml up -d postgres

# Run migrations
cd laravel
php artisan migrate --database=pgsql

# Verify
php artisan migrate:status
```

### 2. Execute Data Migration

```bash
# Dry-run first
php artisan migrate:from-mysql --dry-run

# Migrate all data
php artisan migrate:from-mysql --chunk=5000

# Verify data integrity
# Check record counts in both databases
```

### 3. Run Tests

```bash
# Unit + Feature tests
php artisan test

# Specific model tests
php artisan test --filter=BuildingTest
php artisan test --filter=FleetTest
php artisan test --filter=ResearchTest
```

### 4. API Implementation

Based on Phase 1 plan, implement:
- ✅ Controllers (partially done)
- ⏳ Remaining API endpoints
- ⏳ Request validation
- ⏳ API resources (transformers)
- ⏳ Service layer completion
- ⏳ API documentation

### 5. Performance Testing

```bash
# Load testing
ab -n 1000 -c 10 http://localhost/api/v1/planets

# Query performance
php artisan telescope:install
```

---

## Files Created

### Models (18 new files)
- `laravel/app/Models/Building.php`
- `laravel/app/Models/Ship.php`
- `laravel/app/Models/Defense.php`
- `laravel/app/Models/Research.php`
- `laravel/app/Models/Fleet.php`
- `laravel/app/Models/Report.php`
- `laravel/app/Models/Message.php`
- `laravel/app/Models/Note.php`
- `laravel/app/Models/Buddy.php`
- `laravel/app/Models/Session.php`
- `laravel/app/Models/UserStatistic.php`
- `laravel/app/Models/AllianceStatistic.php`
- `laravel/app/Models/Acs.php`
- `laravel/app/Models/BannedUser.php`
- `laravel/app/Models/Changelog.php`
- `laravel/app/Models/Language.php`
- `laravel/app/Models/Option.php`
- `laravel/app/Models/Premium.php`

### Commands (1 file)
- `laravel/app/Console/Commands/MigrateFromMysql.php`

### Factories (8 files)
- `laravel/database/factories/BuildingFactory.php`
- `laravel/database/factories/ShipFactory.php`
- `laravel/database/factories/DefenseFactory.php`
- `laravel/database/factories/ResearchFactory.php`
- `laravel/database/factories/FleetFactory.php`
- `laravel/database/factories/ReportFactory.php`
- `laravel/database/factories/MessageFactory.php`
- `laravel/database/factories/UserStatisticFactory.php`

### Tests (4 files)
- `laravel/tests/Feature/Models/BuildingTest.php`
- `laravel/tests/Feature/Models/FleetTest.php`
- `laravel/tests/Feature/Models/ResearchTest.php`
- `laravel/tests/Feature/Commands/MigrateFromMysqlTest.php`

### Documentation (1 file)
- `docs/PHASE_3_COMPLETED.md` (this file)

**Total New Files:** 32

---

## Success Criteria ✅

All Phase 3 requirements completed:

- ✅ All 22 Eloquent models created with relationships
- ✅ PostgreSQL JSONB casting implemented
- ✅ Custom scopes for common queries
- ✅ Business logic methods in models
- ✅ Data migration command with chunk processing
- ✅ Dry-run mode for migration testing
- ✅ JSONB transformation for legacy data
- ✅ Model factories with state methods
- ✅ Feature tests for critical models
- ✅ Migration command tests
- ✅ PHP 8.3 strict typing throughout
- ✅ Documentation complete

---

## Performance Considerations

### Model Optimization

**Eager Loading:**
```php
// Avoid N+1 queries
$users = User::with(['planets', 'research', 'statistics'])->get();

$planets = Planet::with(['buildings', 'ships', 'defenses'])->get();

$fleets = Fleet::with(['owner', 'startPlanet', 'endPlanet'])->get();
```

**Select Specific Columns:**
```php
// Only load needed columns
User::select(['user_id', 'user_name', 'user_email'])->get();

Planet::select(['planet_id', 'planet_name', 'user_id'])->get();
```

**Chunk Processing:**
```php
// Process large datasets
User::chunk(1000, function ($users) {
    foreach ($users as $user) {
        // Process user
    }
});
```

### Database Optimization

**Indexes** (already in migrations):
- ✅ Primary keys (auto-indexed)
- ✅ Foreign keys (indexed)
- ✅ Composite indexes for coordinates
- ✅ GIN indexes for JSONB
- ✅ GIN indexes for full-text search

**Query Optimization:**
```php
// Use scopes for complex queries
Fleet::active()->byMission(3)->with('owner')->get();

// Use raw where for JSONB
Fleet::whereRaw("fleet_composition ? '204'")->get();

// Use full-text search
Message::search('important')->get();
```

---

## Migration Rollback Plan

If issues occur during data migration:

```bash
# 1. Stop application
docker-compose down

# 2. Restore PostgreSQL from backup (if exists)
pg_restore -h localhost -U xgproyect -d xgp backup.dump

# 3. Or truncate and re-migrate
php artisan db:wipe --database=pgsql
php artisan migrate --database=pgsql
php artisan migrate:from-mysql

# 4. Or fallback to MySQL temporarily
# .env: DB_CONNECTION=mysql
docker-compose up -d
```

---

## Known Limitations

1. **Large Datasets**: Migration of tables with millions of records may take significant time
   - **Solution**: Use larger chunk sizes (--chunk=10000)
   - **Solution**: Run during low-traffic periods

2. **JSONB Transformation**: Legacy serialized data may have edge cases
   - **Solution**: Dry-run mode to detect issues
   - **Solution**: Manual inspection of critical tables

3. **Foreign Key Constraints**: Must respect dependency order
   - **Solution**: Migration command handles proper ordering
   - **Solution**: Temporary constraint disabling if needed

---

## Support & Resources

- **Phase 1 Plan**: `docs/PHASE_1_PLAN.md`
- **Phase 2 Completed**: `docs/PHASE_2_COMPLETED.md`
- **PostgreSQL Migration**: `docs/POSTGRESQL_MIGRATION.md`
- **Modernization Overview**: `MODERNIZATION.md`
- **Laravel Docs**: https://laravel.com/docs/11.x/eloquent
- **PostgreSQL Docs**: https://www.postgresql.org/docs/16/

---

## License

GPL-3.0-only (same as main project)

---

**Phase 3 Complete! Ready for Phase 4: API Implementation & Production Deployment** 🚀

---

## Quick Reference

### Common Commands

```bash
# Run migrations
php artisan migrate --database=pgsql

# Migrate data from MySQL
php artisan migrate:from-mysql

# Run tests
php artisan test

# Create new model
php artisan make:model ModelName

# Create factory
php artisan make:factory ModelNameFactory

# Create test
php artisan make:test ModelNameTest
```

### Model Relationships

```php
// One-to-Many
$user->planets                    // User has many planets
$planet->user                     // Planet belongs to user

// One-to-One
$user->research                   // User has one research
$planet->buildings                // Planet has one building set

// Many-to-Many (future)
$alliance->members               // Alliance has many users
```

### JSONB Operations

```php
// Store JSONB
$fleet->fleet_composition = ['202' => 100, '204' => 50];
$fleet->save();

// Query JSONB
Fleet::whereRaw("fleet_composition ? '204'")->get();

// Access JSONB
$shipCount = $fleet->fleet_composition['204'];
```

### Full-Text Search

```php
// Search messages
Message::search('alliance')->get();

// Search notes
Note::search('strategy')->get();

// Search changelog
Changelog::search('feature')->get();
```
