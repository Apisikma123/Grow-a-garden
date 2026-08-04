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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garden_id')->constrained('gardens')->cascadeOnDelete();
            $table->foreignId('plant_id')->nullable()->constrained('plants')->nullOnDelete();
            $table->enum('source_type', [
                'WEATHER_RULE',
                'ACTIVITY_RULE',
                'DROUGHT_DETECTOR',
                'LIFECYCLE',
                'SYSTEM',
            ]);
            $table->unsignedBigInteger('source_id')->nullable(); // polymorphic FK
            $table->enum('severity', ['CRITICAL', 'HIGH', 'MEDIUM', 'LOW']);
            $table->text('message');
            $table->enum('status', ['ACTIVE', 'RESOLVED', 'DISMISSED'])->default('ACTIVE');
            $table->timestamp('triggered_at');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index('garden_id');
            $table->index('plant_id');
            $table->index('source_type');
            $table->index('severity');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
