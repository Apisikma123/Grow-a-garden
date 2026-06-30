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
        Schema::create('garden_plots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garden_id')->constrained('gardens')->cascadeOnDelete();
            $table->foreignId('plant_id')->nullable()->constrained('plants')->nullOnDelete();
            $table->string('name');
            $table->enum('shape', ['rectangle', 'circle', 'hexagon', 'custom'])->default('rectangle');
            $table->integer('width')->nullable();
            $table->integer('length')->nullable();
            $table->integer('pos_x')->nullable();
            $table->integer('pos_y')->nullable();
            $table->json('custom_points_json')->nullable();
            $table->timestamps();
            
            $table->index('garden_id');
            $table->index('plant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garden_plots');
    }
};
