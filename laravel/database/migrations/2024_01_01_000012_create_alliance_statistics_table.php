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
        Schema::create('alliance_statistics', function (Blueprint $table) {
            // Primary Key
            $table->id('alliance_stat_id');

            // Foreign Key to alliances
            $table->unsignedBigInteger('alliance_id')->unique();

            // Buildings Statistics
            $table->unsignedBigInteger('alliance_stat_buildings_points')->default(0);
            $table->unsignedInteger('alliance_stat_buildings_rank')->default(0);
            $table->unsignedInteger('alliance_stat_buildings_old_rank')->default(0);

            // Research Statistics
            $table->unsignedBigInteger('alliance_stat_research_points')->default(0);
            $table->unsignedInteger('alliance_stat_research_rank')->default(0);
            $table->unsignedInteger('alliance_stat_research_old_rank')->default(0);

            // Ships Statistics
            $table->unsignedBigInteger('alliance_stat_ships_points')->default(0);
            $table->unsignedInteger('alliance_stat_ships_rank')->default(0);
            $table->unsignedInteger('alliance_stat_ships_old_rank')->default(0);

            // Defenses Statistics
            $table->unsignedBigInteger('alliance_stat_defenses_points')->default(0);
            $table->unsignedInteger('alliance_stat_defenses_rank')->default(0);
            $table->unsignedInteger('alliance_stat_defenses_old_rank')->default(0);

            // Total Statistics
            $table->unsignedBigInteger('alliance_stat_total_points')->default(0);
            $table->unsignedInteger('alliance_stat_total_rank')->default(0);
            $table->unsignedInteger('alliance_stat_total_old_rank')->default(0);

            // Member Count
            $table->unsignedInteger('alliance_stat_member_count')->default(0);

            // Laravel timestamps
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('alliance_id')
                ->references('alliance_id')
                ->on('alliances')
                ->onDelete('cascade');

            // Indexes for alliance leaderboards
            $table->index(['alliance_stat_total_points', 'alliance_stat_total_rank'], 'idx_alliance_total_leaderboard');
            $table->index(['alliance_stat_buildings_points', 'alliance_stat_buildings_rank'], 'idx_alliance_buildings_lb');
            $table->index(['alliance_stat_research_points', 'alliance_stat_research_rank'], 'idx_alliance_research_lb');
            $table->index(['alliance_stat_ships_points', 'alliance_stat_ships_rank'], 'idx_alliance_ships_lb');
            $table->index(['alliance_stat_defenses_points', 'alliance_stat_defenses_rank'], 'idx_alliance_defenses_lb');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alliance_statistics');
    }
};
