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
        Schema::create('premium', function (Blueprint $table) {
            // Primary Key
            $table->id('premium_id');

            // User
            $table->unsignedBigInteger('premium_user_id');

            // Premium Type
            $table->enum('premium_type', [
                'dark_matter',
                'officer_commander',
                'officer_admiral',
                'officer_engineer',
                'officer_geologist',
                'officer_technocrat'
            ])->index();

            // Amount (for dark matter)
            $table->decimal('premium_amount', 20, 2)->default(0);

            // Duration (in days)
            $table->unsignedInteger('premium_duration_days')->nullable();

            // Timestamps
            $table->timestamp('premium_start_time')->useCurrent();
            $table->timestamp('premium_end_time')->nullable()->index();

            // Payment Information
            $table->string('premium_payment_method', 50)->nullable();
            $table->string('premium_transaction_id', 255)->nullable();
            $table->decimal('premium_price', 10, 2)->nullable();
            $table->string('premium_currency', 10)->default('USD');

            // Status
            $table->boolean('premium_active')->default(true)->index();

            // Laravel timestamps
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('premium_user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            // Indexes
            $table->index('premium_user_id');
            $table->index(['premium_user_id', 'premium_type', 'premium_active'], 'idx_user_premium');
            $table->index(['premium_active', 'premium_end_time'], 'idx_active_premium');
            $table->index('premium_transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('premium');
    }
};
