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
        Schema::create('workouts', function (Blueprint $table) {
            $table->id();
            $table->date('filled_at')->nullable()->comment('The date when the workout was filled out');
            $table->date('next_change_at')->nullable()->comment('The date when the workout should be changed next');
            $table->string('goal')->nullable()->comment('The goal of the workout, e.g. "lose weight", "build muscle", etc.');
            $table->string('method')->nullable()->comment('The method of the workout, e.g. "A/B split", "full body", etc.');
            $table->integer('rest_between_sets')->nullable()->comment('The rest time between sets in seconds');
            $table->integer('rest_between_exercises')->nullable()->comment('The rest time between exercises in seconds');
            $table->boolean('is_active')->default(true)->comment('Whether the workout is active or not');
            $table->timestamps();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workouts');
    }
};
