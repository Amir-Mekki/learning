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
            $table->string('sub')->unique()->nullable();
            $table->string('preferred_username')->nullable();
            $table->string('given_name')->nullable();
            $table->string('family_name')->nullable();
            $table->boolean('email_verified')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['sub', 'preferred_username', 'given_name', 'family_name', 'email_verified']);
        });
    }
};
