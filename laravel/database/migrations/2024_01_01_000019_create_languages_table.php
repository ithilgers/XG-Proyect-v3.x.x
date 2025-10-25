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
        Schema::create('languages', function (Blueprint $table) {
            // Primary Key
            $table->id('language_id');

            // Language Code (ISO 639-1)
            $table->string('language_code', 10)->unique();

            // Language Name
            $table->string('language_name', 50);
            $table->string('language_native_name', 50);

            // Status
            $table->boolean('language_active')->default(true)->index();

            // Language File Path
            $table->string('language_file_path', 255)->nullable();

            // Translation Completeness
            $table->unsignedTinyInteger('language_completeness')->default(0)->comment('0-100%');

            // Laravel timestamps
            $table->timestamps();

            // Indexes
            $table->index('language_code');
            $table->index(['language_active', 'language_completeness'], 'idx_available_languages');
        });

        // Insert default languages
        DB::table(DB::getTablePrefix() . 'languages')->insert([
            [
                'language_code' => 'en',
                'language_name' => 'English',
                'language_native_name' => 'English',
                'language_active' => true,
                'language_completeness' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'language_code' => 'de',
                'language_name' => 'German',
                'language_native_name' => 'Deutsch',
                'language_active' => true,
                'language_completeness' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'language_code' => 'es',
                'language_name' => 'Spanish',
                'language_native_name' => 'Español',
                'language_active' => true,
                'language_completeness' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
