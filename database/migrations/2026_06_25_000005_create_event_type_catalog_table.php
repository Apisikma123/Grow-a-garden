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
        Schema::create('event_type_catalog', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();     // WATERING_REMINDER, GERMINATION, dst
            $table->string('label');
            $table->enum('category', ['LIFECYCLE', 'MAINTENANCE', 'WARNING', 'HARVEST']);
            $table->enum('default_priority', ['CRITICAL', 'HIGH', 'MEDIUM', 'LOW']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_type_catalog');
    }
};
