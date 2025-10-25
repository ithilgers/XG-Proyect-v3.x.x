<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('research', function (Blueprint $table) {
            // Primary Key
            $table->id('research_id');

            // Foreign Key to users (research is per user, not per planet)
            $table->unsignedBigInteger('user_id')->unique();

            // Basic Research
            $table->unsignedInteger('research_espionage_technology')->default(0);
            $table->unsignedInteger('research_computer_technology')->default(0);
            $table->unsignedInteger('research_weapons_technology')->default(0);
            $table->unsignedInteger('research_shielding_technology')->default(0);
            $table->unsignedInteger('research_armour_technology')->default(0);

            // Advanced Research
            $table->unsignedInteger('research_energy_technology')->default(0);
            $table->unsignedInteger('research_hyperspace_technology')->default(0);
            $table->unsignedInteger('research_combustion_drive')->default(0);
            $table->unsignedInteger('research_impulse_drive')->default(0);
            $table->unsignedInteger('research_hyperspace_drive')->default(0);

            // Special Research
            $table->unsignedInteger('research_laser_technology')->default(0);
            $table->unsignedInteger('research_ion_technology')->default(0);
            $table->unsignedInteger('research_plasma_technology')->default(0);
            $table->unsignedInteger('research_intergalactic_research_network')->default(0);
            $table->unsignedInteger('research_astrophysics')->default(0);
            $table->unsignedInteger('research_graviton_technology')->default(0);

            // Research Queue (JSONB)
            $table->jsonb('research_queue')->nullable()->comment('Active research queue');
            $table->unsignedBigInteger('research_current_planet_id')->nullable()->comment('Planet where research is being conducted');

            // Laravel timestamps
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('research_current_planet_id')
                ->references('planet_id')
                ->on('planets')
                ->onDelete('set null');

            // Indexes
            $table->index('user_id');
            $table->index('research_queue', null, 'gin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research');
    }
};
