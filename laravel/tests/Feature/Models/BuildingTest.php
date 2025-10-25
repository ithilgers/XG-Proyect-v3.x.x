<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Building;
use App\Models\Planet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuildingTest extends TestCase
{
    use RefreshDatabase;

    public function test_building_belongs_to_planet(): void
    {
        $planet = Planet::factory()->create();
        $building = Building::factory()->create(['planet_id' => $planet->planet_id]);

        $this->assertInstanceOf(Planet::class, $building->planet);
        $this->assertEquals($planet->planet_id, $building->planet->planet_id);
    }

    public function test_get_building_level_returns_correct_level(): void
    {
        $building = Building::factory()->create([
            'building_metal_mine' => 10,
            'building_crystal_mine' => 8,
        ]);

        $this->assertEquals(10, $building->getBuildingLevel(1)); // Metal Mine
        $this->assertEquals(8, $building->getBuildingLevel(2)); // Crystal Mine
        $this->assertEquals(0, $building->getBuildingLevel(999)); // Invalid ID
    }

    public function test_is_building_in_queue_detects_queued_buildings(): void
    {
        $building = Building::factory()->withQueue()->create();

        $this->assertTrue($building->isBuildingInQueue(1));
        $this->assertFalse($building->isBuildingInQueue(2));
    }

    public function test_get_queue_count_returns_correct_count(): void
    {
        $buildingWithoutQueue = Building::factory()->create();
        $buildingWithQueue = Building::factory()->withQueue()->create();

        $this->assertEquals(0, $buildingWithoutQueue->getQueueCount());
        $this->assertEquals(1, $buildingWithQueue->getQueueCount());
    }

    public function test_building_queue_is_stored_as_jsonb(): void
    {
        $building = Building::factory()->withQueue()->create();

        $this->assertIsArray($building->building_queue);
        $this->assertArrayHasKey('building_id', $building->building_queue[0]);
        $this->assertArrayHasKey('level', $building->building_queue[0]);
    }
}
