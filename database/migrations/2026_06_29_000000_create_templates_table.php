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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->integer('duration_min');
            $table->integer('duration_max');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('template_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('templates')->onDelete('cascade');
            $table->integer('stage_order');
            $table->string('stage_name');
            $table->integer('start_day');
            $table->integer('end_day');
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_stages');
        Schema::dropIfExists('templates');
    }
};
