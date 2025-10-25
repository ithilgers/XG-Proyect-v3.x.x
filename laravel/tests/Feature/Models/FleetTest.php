<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Fleet;
use App\Models\User;
use App\Models\Planet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FleetTest extends TestCase
{
    use RefreshDatabase;

    public function test_fleet_belongs_to_owner(): void
    {
        $user = User::factory()->create();
        $fleet = Fleet::factory()->create(['fleet_owner' => $user->user_id]);

        $this->assertInstanceOf(User::class, $fleet->owner);
        $this->assertEquals($user->user_id, $fleet->owner->user_id);
    }

    public function test_fleet_has_start_coordinates_attribute(): void
    {
        $fleet = Fleet::factory()->create([
            'fleet_start_galaxy' => 1,
            'fleet_start_system' => 100,
            'fleet_start_planet' => 5,
        ]);

        $this->assertEquals('1:100:5', $fleet->startCoordinates);
    }

    public function test_fleet_has_end_coordinates_attribute(): void
    {
        $fleet = Fleet::factory()->create([
            'fleet_end_galaxy' => 2,
            'fleet_end_system' => 200,
            'fleet_end_planet' => 10,
        ]);

        $this->assertEquals('2:200:10', $fleet->endCoordinates);
    }

    public function test_has_arrived_returns_true_when_past_end_time(): void
    {
        $arrivedFleet = Fleet::factory()->arrived()->create();
        $activeFleet = Fleet::factory()->create(['fleet_end_time' => now()->addHours(2)]);

        $this->assertTrue($arrivedFleet->hasArrived());
        $this->assertFalse($activeFleet->hasArrived());
    }

    public function test_is_returning_returns_true_when_mess_is_one(): void
    {
        $returningFleet = Fleet::factory()->returning()->create();
        $normalFleet = Fleet::factory()->create(['fleet_mess' => 0]);

        $this->assertTrue($returningFleet->isReturning());
        $this->assertFalse($normalFleet->isReturning());
    }

    public function test_get_ship_count_from_composition(): void
    {
        $fleet = Fleet::factory()->create([
            'fleet_composition' => [
                '202' => 10,
                '204' => 50,
            ],
        ]);

        $this->assertEquals(10, $fleet->getShipCount(202));
        $this->assertEquals(50, $fleet->getShipCount(204));
        $this->assertEquals(0, $fleet->getShipCount(999));
    }

    public function test_scope_active_returns_only_active_fleets(): void
    {
        Fleet::factory()->create(['fleet_end_time' => now()->addHours(2)]);
        Fleet::factory()->arrived()->create();

        $activeFleets = Fleet::active()->get();

        $this->assertCount(1, $activeFleets);
    }

    public function test_fleet_composition_is_stored_as_jsonb(): void
    {
        $fleet = Fleet::factory()->create([
            'fleet_composition' => ['202' => 100, '204' => 50],
        ]);

        $this->assertIsArray($fleet->fleet_composition);
        $this->assertArrayHasKey('202', $fleet->fleet_composition);
    }
}
