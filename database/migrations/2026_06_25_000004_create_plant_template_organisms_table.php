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
        Schema::create('plant_template_organisms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_template_id')->constrained('plant_templates')->cascadeOnDelete();
            $table->string('name');                       // Antraknosa / Ulat Grayak
            $table->enum('type', ['PEST', 'DISEASE']);
            $table->timestamps();

            $table->index('plant_template_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plant_template_organisms');
    }
};
