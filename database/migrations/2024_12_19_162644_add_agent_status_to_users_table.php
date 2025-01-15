<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('agent_status')->nullable()->after('role');
            $table->boolean('is_online')->default(false)->after('agent_status');
            $table->boolean('is_available')->default(true)->after('is_online');
            $table->timestamp('last_activity_at')->nullable()->after('is_available');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['agent_status', 'is_online', 'is_available', 'last_activity_at']);
        });
    }
};
