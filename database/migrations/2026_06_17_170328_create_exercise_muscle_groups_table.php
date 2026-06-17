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
        Schema::create('exercise_muscle_groups', function (Blueprint $table) {
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('muscle_group_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->primary(['exercise_id', 'muscle_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_muscle_groups');
    }
};
