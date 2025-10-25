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
        Schema::create('buildings', function (Blueprint $table) {
            // Primary Key
            $table->id('building_id');

            // Foreign Key to planets
            $table->unsignedBigInteger('planet_id')->unique();

            // Resource Buildings
            $table->unsignedInteger('building_metal_mine')->default(0);
            $table->unsignedInteger('building_crystal_mine')->default(0);
            $table->unsignedInteger('building_deuterium_synthesizer')->default(0);
            $table->unsignedInteger('building_solar_plant')->default(0);
            $table->unsignedInteger('building_fusion_reactor')->default(0);

            // Storage Buildings
            $table->unsignedInteger('building_metal_storage')->default(0);
            $table->unsignedInteger('building_crystal_storage')->default(0);
            $table->unsignedInteger('building_deuterium_storage')->default(0);

            // Facilities
            $table->unsignedInteger('building_robotics_factory')->default(0);
            $table->unsignedInteger('building_shipyard')->default(0);
            $table->unsignedInteger('building_research_lab')->default(0);
            $table->unsignedInteger('building_alliance_depot')->default(0);
            $table->unsignedInteger('building_missile_silo')->default(0);
            $table->unsignedInteger('building_nanite_factory')->default(0);
            $table->unsignedInteger('building_terraformer')->default(0);
            $table->unsignedInteger('building_space_dock')->default(0);

            // Moon Buildings
            $table->unsignedInteger('building_lunar_base')->default(0);
            $table->unsignedInteger('building_sensor_phalanx')->default(0);
            $table->unsignedInteger('building_jump_gate')->default(0);

            // Building Queue (JSONB for flexibility)
            $table->jsonb('building_queue')->nullable()->comment('Active building queue');

            // Laravel timestamps
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('planet_id')
                ->references('planet_id')
                ->on('planets')
                ->onDelete('cascade');

            // Indexes
            $table->index('planet_id');
            $table->index('building_queue', null, 'gin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
