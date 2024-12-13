<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // text, number, date, select, etc.
            $table->json('options')->nullable(); // For select type fields
            $table->boolean('is_required')->default(false);
            $table->timestamps();
        });

        Schema::create('contact_custom_field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            $table->foreignId('custom_field_id')->constrained()->onDelete('cascade');
            $table->text('value');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contact_custom_field_values');
        Schema::dropIfExists('custom_fields');
    }
};
