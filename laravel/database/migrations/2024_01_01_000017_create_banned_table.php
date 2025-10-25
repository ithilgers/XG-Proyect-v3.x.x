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
        Schema::create('banned', function (Blueprint $table) {
            // Primary Key
            $table->id('banned_id');

            // Banned User
            $table->unsignedBigInteger('banned_user_id');

            // Admin Who Banned
            $table->unsignedBigInteger('banned_by_admin_id')->nullable();

            // Ban Details
            $table->text('banned_reason');
            $table->timestamp('banned_at')->useCurrent();
            $table->timestamp('banned_until')->nullable()->comment('null = permanent ban');

            // Ban Type
            $table->enum('banned_type', ['account', 'ip', 'vacation_abuse', 'pushing'])->default('account');

            // IP Address (if IP ban)
            $table->string('banned_ip', 45)->nullable();

            // Status
            $table->boolean('banned_active')->default(true)->index();

            // Laravel timestamps
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('banned_user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('banned_by_admin_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');

            // Indexes
            $table->index('banned_user_id');
            $table->index(['banned_active', 'banned_until'], 'idx_active_bans');
            $table->index('banned_ip');
            $table->index('banned_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banned');
    }
};
