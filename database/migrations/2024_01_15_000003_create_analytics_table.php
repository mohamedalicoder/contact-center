<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->string('metric_name', 100);
            $table->string('metric_type', 50); // count, average, percentage, duration
            $table->decimal('value', 10, 2);
            $table->json('dimensions')->nullable(); // store additional context like agent_id, department, etc.
            $table->timestamp('measured_at');
            $table->timestamps();
            
            // Add indexes for better query performance
            $table->index(['metric_name', 'measured_at']);
        });

        // Create analytics_summaries table for aggregated data
        Schema::create('analytics_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('metric_name', 100);
            $table->string('period_type', 20); // daily, weekly, monthly
            $table->timestamp('period_start');
            $table->timestamp('period_end');
            $table->decimal('min_value', 10, 2);
            $table->decimal('max_value', 10, 2);
            $table->decimal('avg_value', 10, 2);
            $table->json('summary_data')->nullable();
            $table->timestamps();

            $table->unique(['metric_name', 'period_type', 'period_start'], 'analytics_summaries_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('analytics_summaries');
        Schema::dropIfExists('analytics');
    }
};
