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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('source');                           // e.g., "Salary", "Freelance"
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_type')->nullable();      // RecurrenceType enum
            $table->timestamps();
            
            // Sync tracking (for mobile sync)
            $table->unsignedBigInteger('remote_id')->nullable();
            $table->boolean('is_synced')->default(true);
            
            // Indexes
            $table->index(['user_id', 'date']);
            $table->index(['user_id', 'source']);
            $table->index(['user_id', 'is_recurring']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
