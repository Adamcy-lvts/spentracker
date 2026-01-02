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
        Schema::create('budget_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained()->onDelete('cascade');
            $table->string('alert_type');                       // BudgetAlertType enum
            $table->timestamp('triggered_at')->useCurrent();
            $table->boolean('is_dismissed')->default(false);
            $table->decimal('spent_amount', 15, 2);
            $table->decimal('budget_amount', 15, 2);
            $table->timestamps();
            
            // Index for quick alert retrieval
            $table->index(['budget_id', 'alert_type', 'is_dismissed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_alerts');
    }
};
