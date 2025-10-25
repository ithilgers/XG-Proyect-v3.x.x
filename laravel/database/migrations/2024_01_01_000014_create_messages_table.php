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
        Schema::create('messages', function (Blueprint $table) {
            // Primary Key
            $table->id('message_id');

            // Sender and Receiver
            $table->unsignedBigInteger('message_sender');
            $table->unsignedBigInteger('message_receiver');

            // Message Type
            $table->unsignedTinyInteger('message_type')->default(0)->comment('0=normal, 1=alliance, 2=system, etc.');

            // Message Content
            $table->string('message_subject', 255);
            $table->text('message_text');

            // Status
            $table->boolean('message_read')->default(false)->index();
            $table->boolean('message_sender_deleted')->default(false);
            $table->boolean('message_receiver_deleted')->default(false);

            // Laravel timestamps
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('message_sender')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('message_receiver')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            // Indexes
            $table->index('message_sender');
            $table->index('message_receiver');
            $table->index(['message_receiver', 'message_read', 'message_receiver_deleted'], 'idx_inbox');
            $table->index(['message_sender', 'message_sender_deleted'], 'idx_outbox');
            $table->index(['message_type', 'message_receiver'], 'idx_message_type');
        });

        // Add full-text search for messages (PostgreSQL)
        DB::statement("
            ALTER TABLE " . DB::getTablePrefix() . "messages
            ADD COLUMN search_vector tsvector
            GENERATED ALWAYS AS (
                to_tsvector('english',
                    coalesce(message_subject, '') || ' ' ||
                    coalesce(message_text, '')
                )
            ) STORED
        ");

        DB::statement("
            CREATE INDEX idx_messages_search ON " . DB::getTablePrefix() . "messages
            USING GIN (search_vector)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
