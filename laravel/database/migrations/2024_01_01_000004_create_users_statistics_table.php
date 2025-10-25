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
        Schema::create('users_statistics', function (Blueprint $table) {
            // Primary Key
            $table->id('stat_id');

            // Foreign Key to users
            $table->unsignedBigInteger('user_id')->unique();

            // Buildings Statistics
            $table->unsignedBigInteger('stat_buildings_points')->default(0);
            $table->unsignedInteger('stat_buildings_rank')->default(0);
            $table->unsignedInteger('stat_buildings_old_rank')->default(0);

            // Research Statistics
            $table->unsignedBigInteger('stat_research_points')->default(0);
            $table->unsignedInteger('stat_research_rank')->default(0);
            $table->unsignedInteger('stat_research_old_rank')->default(0);

            // Ships Statistics
            $table->unsignedBigInteger('stat_ships_points')->default(0);
            $table->unsignedInteger('stat_ships_rank')->default(0);
            $table->unsignedInteger('stat_ships_old_rank')->default(0);

            // Defenses Statistics
            $table->unsignedBigInteger('stat_defenses_points')->default(0);
            $table->unsignedInteger('stat_defenses_rank')->default(0);
            $table->unsignedInteger('stat_defenses_old_rank')->default(0);

            // Total Statistics
            $table->unsignedBigInteger('stat_total_points')->default(0);
            $table->unsignedInteger('stat_total_rank')->default(0);
            $table->unsignedInteger('stat_total_old_rank')->default(0);

            // Battle Statistics
            $table->unsignedBigInteger('stat_units_destroyed')->default(0);
            $table->unsignedBigInteger('stat_units_lost')->default(0);

            // Laravel timestamps
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            // Indexes for leaderboards (with PostgreSQL window functions)
            $table->index(['stat_total_points', 'stat_total_rank'], 'idx_total_leaderboard');
            $table->index(['stat_buildings_points', 'stat_buildings_rank'], 'idx_buildings_leaderboard');
            $table->index(['stat_research_points', 'stat_research_rank'], 'idx_research_leaderboard');
            $table->index(['stat_ships_points', 'stat_ships_rank'], 'idx_ships_leaderboard');
            $table->index(['stat_defenses_points', 'stat_defenses_rank'], 'idx_defenses_leaderboard');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_statistics');
    }
};
