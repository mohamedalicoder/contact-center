<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('priority')->default(1);
            $table->integer('max_size')->nullable();
            $table->integer('wait_time_threshold')->default(300); // in seconds
            $table->boolean('is_active')->default(true);
            $table->json('business_hours')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('queues');
    }
};
