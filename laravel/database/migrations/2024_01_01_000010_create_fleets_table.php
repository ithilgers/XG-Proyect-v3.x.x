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
        Schema::create('fleets', function (Blueprint $table) {
            // Primary Key
            $table->id('fleet_id');

            // Owner
            $table->unsignedBigInteger('fleet_owner');

            // Mission Type
            $table->unsignedTinyInteger('fleet_mission')->comment('1=Attack, 2=Transport, 3=Deploy, etc.');
            $table->unsignedTinyInteger('fleet_mission_status')->default(0)->comment('0=Active, 1=Returning');

            // Start Location
            $table->unsignedBigInteger('fleet_start_planet_id');
            $table->unsignedSmallInteger('fleet_start_galaxy');
            $table->unsignedSmallInteger('fleet_start_system');
            $table->unsignedSmallInteger('fleet_start_planet');
            $table->unsignedTinyInteger('fleet_start_type')->default(1)->comment('1=Planet, 2=Debris, 3=Moon');

            // End Location
            $table->unsignedBigInteger('fleet_end_planet_id')->nullable();
            $table->unsignedSmallInteger('fleet_end_galaxy');
            $table->unsignedSmallInteger('fleet_end_system');
            $table->unsignedSmallInteger('fleet_end_planet');
            $table->unsignedTinyInteger('fleet_end_type')->default(1)->comment('1=Planet, 2=Debris, 3=Moon');

            // Target Owner (for attacks)
            $table->unsignedBigInteger('fleet_target_owner')->nullable();

            // Timing
            $table->timestamp('fleet_start_time');
            $table->timestamp('fleet_end_time')->index();
            $table->unsignedInteger('fleet_end_stay')->default(0)->comment('Stay time for deploy missions');

            // Resources
            $table->decimal('fleet_resource_metal', 20, 2)->default(0);
            $table->decimal('fleet_resource_crystal', 20, 2)->default(0);
            $table->decimal('fleet_resource_deuterium', 20, 2)->default(0);

            // Fleet Composition (JSONB for flexibility)
            $table->jsonb('fleet_composition')->comment('Ship types and amounts');

            // Fleet Group (for ACS)
            $table->unsignedBigInteger('fleet_group_id')->nullable();

            // Laravel timestamps
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('fleet_owner')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('fleet_start_planet_id')
                ->references('planet_id')
                ->on('planets')
                ->onDelete('cascade');

            $table->foreign('fleet_end_planet_id')
                ->references('planet_id')
                ->on('planets')
                ->onDelete('set null');

            $table->foreign('fleet_target_owner')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');

            // Indexes for fleet queries
            $table->index('fleet_owner');
            $table->index(['fleet_end_time', 'fleet_mission_status'], 'idx_fleet_arrival');
            $table->index(['fleet_end_galaxy', 'fleet_end_system', 'fleet_end_planet'], 'idx_fleet_destination');
            $table->index('fleet_composition', null, 'gin');
            $table->index('fleet_group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fleets');
    }
};
