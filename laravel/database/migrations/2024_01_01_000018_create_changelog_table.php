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
        Schema::create('changelog', function (Blueprint $table) {
            // Primary Key
            $table->id('changelog_id');

            // Version Information
            $table->string('changelog_version', 20)->unique();
            $table->string('changelog_title', 255);

            // Changelog Content
            $table->text('changelog_description');

            // Language
            $table->string('changelog_lang', 10)->default('en');

            // Publication Date
            $table->timestamp('changelog_date')->useCurrent();

            // Visibility
            $table->boolean('changelog_visible')->default(true)->index();

            // Laravel timestamps
            $table->timestamps();

            // Indexes
            $table->index(['changelog_visible', 'changelog_date'], 'idx_visible_changelog');
            $table->index('changelog_lang');
        });

        // Add full-text search for changelog (PostgreSQL)
        DB::statement("
            ALTER TABLE " . DB::getTablePrefix() . "changelog
            ADD COLUMN search_vector tsvector
            GENERATED ALWAYS AS (
                to_tsvector('english',
                    coalesce(changelog_title, '') || ' ' ||
                    coalesce(changelog_description, '')
                )
            ) STORED
        ");

        DB::statement("
            CREATE INDEX idx_changelog_search ON " . DB::getTablePrefix() . "changelog
            USING GIN (search_vector)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('changelog');
    }
};
