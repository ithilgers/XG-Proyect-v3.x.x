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
        Schema::create('buddys', function (Blueprint $table) {
            // Primary Key
            $table->id('buddy_id');

            // Relationship
            $table->unsignedBigInteger('buddy_sender');
            $table->unsignedBigInteger('buddy_receiver');

            // Status
            $table->boolean('buddy_status')->default(false)->comment('false=pending, true=accepted');

            // Request Text
            $table->text('buddy_request_text')->nullable();

            // Laravel timestamps
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('buddy_sender')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('buddy_receiver')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            // Indexes
            $table->index('buddy_sender');
            $table->index('buddy_receiver');
            $table->index(['buddy_sender', 'buddy_receiver'], 'idx_buddy_relationship');
            $table->index(['buddy_receiver', 'buddy_status'], 'idx_buddy_pending');

            // Unique constraint to prevent duplicate buddy requests
            $table->unique(['buddy_sender', 'buddy_receiver'], 'unique_buddy_pair');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buddys');
    }
};
