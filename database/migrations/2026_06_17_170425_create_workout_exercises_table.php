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
        Schema::create('workout_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_section_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('muscle_group_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code')->nullable()->comment('Code of the exercise, e.g., "A1", "B2"');
            $table->unsignedInteger('order')->default(0)->comment('The order of the exercise in the workout section');
            $table->unsignedTinyInteger('sets')->default(3)->comment('Number of sets');
            $table->string('reps')->default('8-12')->comment('Reps, e.g., "8-12", "AMRAP", "Failure", etc.');
            $table->decimal('load', 6, 2)->nullable()->comment('Load, default unit is kg');
            $table->string('load_unit')->default('kg')->comment('Unit of the load, e.g., "kg", "lbs", "bodyweight", etc.');
            $table->integer('rest_seconds')->nullable()->comment('Rest time in seconds between sets');
            $table->text('notes')->nullable()->comment('Additional notes for the exercise, e.g., "Use a spotter", "Focus on form", etc.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_exercises');
    }
};
