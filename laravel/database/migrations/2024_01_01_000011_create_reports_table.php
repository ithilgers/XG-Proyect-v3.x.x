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
        Schema::create('reports', function (Blueprint $table) {
            // Primary Key
            $table->id('report_id');

            // Report Type
            $table->enum('report_type', ['combat', 'espionage', 'transport', 'deployment', 'harvest', 'colonization'])->index();

            // Report Owner
            $table->unsignedBigInteger('report_owner');

            // Report Data (JSONB for flexible structure)
            $table->jsonb('report_data')->comment('Complete report data including battle rounds, loot, etc.');

            // Report Summary (for quick lookups)
            $table->string('report_result', 50)->nullable()->comment('attacker_won, defender_won, draw');
            $table->decimal('report_total_loot', 20, 2)->nullable();

            // Coordinates
            $table->unsignedSmallInteger('report_galaxy');
            $table->unsignedSmallInteger('report_system');
            $table->unsignedSmallInteger('report_planet');

            // Read Status
            $table->boolean('report_read')->default(false)->index();

            // Laravel timestamps
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('report_owner')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            // Indexes
            $table->index('report_owner');
            $table->index(['report_owner', 'report_read', 'created_at'], 'idx_user_reports');
            $table->index(['report_galaxy', 'report_system', 'report_planet'], 'idx_report_coordinates');
            $table->index('report_data', null, 'gin');
        });

        // Add full-text search for reports (PostgreSQL)
        DB::statement("
            CREATE INDEX idx_reports_search ON " . DB::getTablePrefix() . "reports
            USING GIN ((report_data::text))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
