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
        Schema::create('defenses', function (Blueprint $table) {
            // Primary Key
            $table->id('defense_id');

            // Foreign Key to planets
            $table->unsignedBigInteger('planet_id')->unique();

            // Defense Systems
            $table->unsignedBigInteger('defense_rocket_launcher')->default(0);
            $table->unsignedBigInteger('defense_light_laser')->default(0);
            $table->unsignedBigInteger('defense_heavy_laser')->default(0);
            $table->unsignedBigInteger('defense_gauss_cannon')->default(0);
            $table->unsignedBigInteger('defense_ion_cannon')->default(0);
            $table->unsignedBigInteger('defense_plasma_turret')->default(0);
            $table->unsignedBigInteger('defense_small_shield_dome')->default(0);
            $table->unsignedBigInteger('defense_large_shield_dome')->default(0);

            // Missiles
            $table->unsignedBigInteger('defense_anti_ballistic_missile')->default(0);
            $table->unsignedBigInteger('defense_interplanetary_missile')->default(0);

            // Defense Queue (JSONB for flexibility)
            $table->jsonb('defense_queue')->nullable()->comment('Active defense queue');

            // Laravel timestamps
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('planet_id')
                ->references('planet_id')
                ->on('planets')
                ->onDelete('cascade');

            // Indexes
            $table->index('planet_id');
            $table->index('defense_queue', null, 'gin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defenses');
    }
};
