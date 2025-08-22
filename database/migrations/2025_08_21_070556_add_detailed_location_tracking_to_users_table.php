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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('last_login_latitude', 10, 8)->nullable();
            $table->decimal('last_login_longitude', 11, 8)->nullable();
            $table->string('last_login_city')->nullable();
            $table->string('last_login_country')->nullable();
            $table->string('last_login_device_type')->nullable(); // Android, iOS, Web
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_login_latitude', 
                'last_login_longitude', 
                'last_login_city', 
                'last_login_country', 
                'last_login_device_type'
            ]);
        });
    }
};
