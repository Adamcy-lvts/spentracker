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
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->dropPrimary(['email']);
            $table->string('email')->nullable()->change();
            $table->string('phone_number')->nullable()->after('email');
            $table->index(['email', 'phone_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->dropIndex(['email', 'phone_number']);
            $table->dropColumn('phone_number');
            $table->string('email')->nullable(false)->change();
            $table->primary('email');
        });
    }
};
