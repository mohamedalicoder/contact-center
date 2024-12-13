<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('agent_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');
            $table->integer('proficiency_level')->default(1); // 1-5
            $table->timestamps();
        });

        Schema::create('queue_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_id')->constrained()->onDelete('cascade');
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');
            $table->integer('minimum_proficiency')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('queue_skills');
        Schema::dropIfExists('agent_skills');
        Schema::dropIfExists('skills');
    }
};
