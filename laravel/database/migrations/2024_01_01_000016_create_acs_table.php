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
        Schema::create('acs', function (Blueprint $table) {
            // Primary Key
            $table->id('acs_id');

            // ACS Group Name
            $table->string('acs_name', 50);

            // ACS Owner/Creator
            $table->unsignedBigInteger('acs_owner');

            // Target Coordinates
            $table->unsignedSmallInteger('acs_galaxy');
            $table->unsignedSmallInteger('acs_system');
            $table->unsignedSmallInteger('acs_planet');

            // Members (JSONB array of user IDs)
            $table->jsonb('acs_members')->comment('Array of participating user IDs');

            // Invited Users (JSONB array)
            $table->jsonb('acs_invited')->nullable()->comment('Array of invited but not yet joined user IDs');

            // Status
            $table->boolean('acs_active')->default(true);

            // Laravel timestamps
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('acs_owner')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            // Indexes
            $table->index('acs_owner');
            $table->index(['acs_galaxy', 'acs_system', 'acs_planet'], 'idx_acs_target');
            $table->index('acs_members', null, 'gin');
            $table->index('acs_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acs');
    }
};
