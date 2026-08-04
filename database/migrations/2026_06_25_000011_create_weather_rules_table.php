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
        Schema::create('weather_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // Risiko Jamur
            $table->string('weather_field');       // humidity / wind_speed / temperature
            $table->enum('operator', ['>', '<', '>=', '<=', '==', '!=']);
            $table->decimal('threshold', 8, 2);
            $table->enum('severity', ['CRITICAL', 'HIGH', 'MEDIUM', 'LOW']);
            $table->text('message');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_rules');
    }
};
