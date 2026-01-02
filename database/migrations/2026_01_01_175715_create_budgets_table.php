<?php

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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('budget_type')->default('category'); // BudgetType enum
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('period_type')->default('monthly');  // BudgetPeriodType enum
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_recurring')->default(true);
            
            // Alert thresholds
            $table->boolean('alert_at_80')->default(true);
            $table->boolean('alert_at_100')->default(true);
            $table->boolean('alert_over_budget')->default(true);
            $table->boolean('enable_notifications')->default(true);
            
            $table->timestamps();
            
            // Sync tracking
            $table->unsignedBigInteger('remote_id')->nullable();
            $table->boolean('is_synced')->default(true);
            
            // Indexes
            $table->index(['user_id', 'budget_type', 'start_date']);
            $table->index(['user_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
