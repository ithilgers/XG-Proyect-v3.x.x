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
        Schema::create('options', function (Blueprint $table) {
            // Primary Key
            $table->id('option_id');

            // Option Key (unique identifier)
            $table->string('option_name', 255)->unique();

            // Option Value (can be any type, stored as text/json)
            $table->text('option_value');

            // Option Type for type casting
            $table->enum('option_type', ['string', 'integer', 'boolean', 'array', 'json'])->default('string');

            // Option Category
            $table->string('option_category', 50)->nullable()->comment('game, system, email, etc.');

            // Description
            $table->text('option_description')->nullable();

            // Auto-load this option?
            $table->boolean('option_autoload')->default(true)->index();

            // Laravel timestamps
            $table->timestamps();

            // Indexes
            $table->index('option_name');
            $table->index(['option_category', 'option_autoload'], 'idx_category_options');
        });

        // Insert default game options
        DB::table(DB::getTablePrefix() . 'options')->insert([
            [
                'option_name' => 'game_name',
                'option_value' => 'XG Proyect',
                'option_type' => 'string',
                'option_category' => 'game',
                'option_description' => 'Name of the game',
                'option_autoload' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'option_name' => 'game_speed',
                'option_value' => '1',
                'option_type' => 'integer',
                'option_category' => 'game',
                'option_description' => 'Game speed multiplier',
                'option_autoload' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'option_name' => 'fleet_speed',
                'option_value' => '1',
                'option_type' => 'integer',
                'option_category' => 'game',
                'option_description' => 'Fleet speed multiplier',
                'option_autoload' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'option_name' => 'resource_multiplier',
                'option_value' => '1',
                'option_type' => 'integer',
                'option_category' => 'game',
                'option_description' => 'Resource production multiplier',
                'option_autoload' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'option_name' => 'registration_enabled',
                'option_value' => 'true',
                'option_type' => 'boolean',
                'option_category' => 'system',
                'option_description' => 'Allow new user registrations',
                'option_autoload' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'option_name' => 'maintenance_mode',
                'option_value' => 'false',
                'option_type' => 'boolean',
                'option_category' => 'system',
                'option_description' => 'Enable maintenance mode',
                'option_autoload' => true,
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
        Schema::dropIfExists('options');
    }
};
