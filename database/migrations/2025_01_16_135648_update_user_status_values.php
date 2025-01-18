<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any existing status values to 'offline'
        DB::table('users')->update(['status' => 'offline']);
        
        // Now modify the column
        DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('online', 'offline', 'away') NOT NULL DEFAULT 'offline'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, update any existing status values to 'inactive'
        DB::table('users')->update(['status' => 'inactive']);
        
        // Now modify the column back
        DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('active', 'inactive') NOT NULL DEFAULT 'inactive'");
    }
};
