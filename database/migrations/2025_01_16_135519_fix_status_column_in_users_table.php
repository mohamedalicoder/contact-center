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
            // First drop the existing status column
            $table->dropColumn('status');
        });

        Schema::table('users', function (Blueprint $table) {
            // Then recreate it with the correct enum values
            $table->enum('status', ['online', 'offline', 'away'])->default('offline')->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->string('status')->after('role');
        });
    }
};
