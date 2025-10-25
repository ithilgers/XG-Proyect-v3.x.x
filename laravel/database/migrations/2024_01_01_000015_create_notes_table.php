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
        Schema::create('notes', function (Blueprint $table) {
            // Primary Key
            $table->id('note_id');

            // Owner
            $table->unsignedBigInteger('note_owner');

            // Note Content
            $table->string('note_title', 255);
            $table->text('note_text');

            // Priority/Color
            $table->unsignedTinyInteger('note_priority')->default(0)->comment('0=normal, 1=important, 2=urgent');
            $table->string('note_color', 20)->nullable();

            // Laravel timestamps
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('note_owner')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            // Indexes
            $table->index('note_owner');
            $table->index(['note_owner', 'note_priority'], 'idx_user_notes');
        });

        // Add full-text search for notes (PostgreSQL)
        DB::statement("
            ALTER TABLE " . DB::getTablePrefix() . "notes
            ADD COLUMN search_vector tsvector
            GENERATED ALWAYS AS (
                to_tsvector('english',
                    coalesce(note_title, '') || ' ' ||
                    coalesce(note_text, '')
                )
            ) STORED
        ");

        DB::statement("
            CREATE INDEX idx_notes_search ON " . DB::getTablePrefix() . "notes
            USING GIN (search_vector)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
