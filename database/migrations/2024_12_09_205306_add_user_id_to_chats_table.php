<?php

// database/migrations/YYYY_MM_DD_HHMMSS_add_user_id_to_chats_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToChatsTable extends Migration
{
    public function up()
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Adds user_id and sets up the foreign key constraint
        });
    }

    public function down()
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Drops the foreign key constraint
            $table->dropColumn('user_id'); // Drops the user_id column
        });
    }
}
