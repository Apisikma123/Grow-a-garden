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
        Schema::create('weather_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garden_id')->constrained('gardens')->cascadeOnDelete();
            $table->timestamp('recorded_at');
            $table->decimal('temperature', 5, 2);       // °C
            $table->decimal('humidity', 5, 2);           // %
            $table->decimal('rain_probability', 5, 2);   // %
            $table->decimal('precipitation', 6, 2);      // mm
            $table->decimal('wind_speed', 5, 2);         // km/h
            $table->string('source')->default('Open-Meteo');
            $table->timestamps();

            $table->index('garden_id');
            $table->index('recorded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_snapshots');
    }
};
