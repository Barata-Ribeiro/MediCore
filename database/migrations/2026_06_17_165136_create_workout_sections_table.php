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
        Schema::create('workout_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('The name of the workout section, e.g. "Superior", "Inferior", "Day A", etc.');
            $table->unsignedInteger('order')->default(0)->comment('The order of the workout section in the workout');
            $table->timestamps();

            $table->foreignId('workout_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_sections');
    }
};
