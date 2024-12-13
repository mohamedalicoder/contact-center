<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('queue_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_id')->constrained()->onDelete('cascade');
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('type'); // call, chat, ticket
            $table->string('status'); // waiting, in_progress, completed, abandoned
            $table->integer('priority')->default(1);
            $table->json('metadata')->nullable();
            $table->timestamp('entered_at');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('queue_items');
    }
};
