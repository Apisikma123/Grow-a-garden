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
        Schema::create('weather_anomalies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garden_id')->constrained('gardens')->cascadeOnDelete();
            $table->enum('type', ['DROUGHT', 'HEAVY_RAIN_STREAK']);
            $table->date('start_date');
            $table->integer('consecutive_days')->default(0);
            $table->enum('status', ['ONGOING', 'RESOLVED'])->default('ONGOING');
            $table->timestamp('detected_at');       // oleh DroughtDetector
            $table->timestamps();

            $table->index('garden_id');
            $table->index('type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_anomalies');
    }
};
