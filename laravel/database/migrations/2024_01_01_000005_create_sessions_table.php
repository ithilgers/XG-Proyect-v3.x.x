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
        Schema::create('sessions', function (Blueprint $table) {
            // Session ID as primary key
            $table->string('id', 255)->primary();

            // User ID (nullable for guest sessions)
            $table->unsignedBigInteger('user_id')->nullable();

            // Session data
            $table->text('payload');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            // Last activity timestamp
            $table->integer('last_activity')->index();

            // Foreign Key Constraint
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            // Index for user sessions
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
