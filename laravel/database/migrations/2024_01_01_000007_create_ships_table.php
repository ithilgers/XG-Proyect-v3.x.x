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
        Schema::create('ships', function (Blueprint $table) {
            // Primary Key
            $table->id('ship_id');

            // Foreign Key to planets
            $table->unsignedBigInteger('planet_id')->unique();

            // Civil Ships
            $table->unsignedBigInteger('ship_small_cargo')->default(0);
            $table->unsignedBigInteger('ship_large_cargo')->default(0);
            $table->unsignedBigInteger('ship_colony_ship')->default(0);
            $table->unsignedBigInteger('ship_recycler')->default(0);
            $table->unsignedBigInteger('ship_espionage_probe')->default(0);
            $table->unsignedBigInteger('ship_solar_satellite')->default(0);

            // Combat Ships
            $table->unsignedBigInteger('ship_light_fighter')->default(0);
            $table->unsignedBigInteger('ship_heavy_fighter')->default(0);
            $table->unsignedBigInteger('ship_cruiser')->default(0);
            $table->unsignedBigInteger('ship_battleship')->default(0);
            $table->unsignedBigInteger('ship_battlecruiser')->default(0);
            $table->unsignedBigInteger('ship_bomber')->default(0);
            $table->unsignedBigInteger('ship_destroyer')->default(0);
            $table->unsignedBigInteger('ship_deathstar')->default(0);

            // Shipyard Queue (JSONB for flexibility)
            $table->jsonb('ship_queue')->nullable()->comment('Active shipyard queue');

            // Laravel timestamps
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('planet_id')
                ->references('planet_id')
                ->on('planets')
                ->onDelete('cascade');

            // Indexes
            $table->index('planet_id');
            $table->index('ship_queue', null, 'gin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ships');
    }
};
