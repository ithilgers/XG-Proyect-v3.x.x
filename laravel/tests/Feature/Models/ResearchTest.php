<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Research;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_research_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $research = Research::factory()->create(['user_id' => $user->user_id]);

        $this->assertInstanceOf(User::class, $research->user);
        $this->assertEquals($user->user_id, $research->user->user_id);
    }

    public function test_get_research_level_returns_correct_level(): void
    {
        $research = Research::factory()->create([
            'research_espionage_technology' => 10,
            'research_weapons_technology' => 15,
        ]);

        $this->assertEquals(10, $research->getResearchLevel(106)); // Espionage
        $this->assertEquals(15, $research->getResearchLevel(109)); // Weapons
        $this->assertEquals(0, $research->getResearchLevel(999)); // Invalid
    }

    public function test_has_active_research_detects_active_research(): void
    {
        $activeResearch = Research::factory()->withActiveResearch()->create();
        $inactiveResearch = Research::factory()->create(['research_current_research' => null]);

        $this->assertTrue($activeResearch->hasActiveResearch());
        $this->assertFalse($inactiveResearch->hasActiveResearch());
    }

    public function test_get_max_planets_calculates_correctly(): void
    {
        $research = Research::factory()->create(['research_astrophysics' => 5]);

        $this->assertEquals(6, $research->getMaxPlanets());
    }

    public function test_get_max_expeditions_calculates_correctly(): void
    {
        $research = Research::factory()->create(['research_astrophysics' => 9]);

        $this->assertEquals(3, $research->getMaxExpeditions());
    }
}
