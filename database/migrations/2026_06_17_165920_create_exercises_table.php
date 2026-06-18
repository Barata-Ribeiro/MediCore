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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->index()->comment('The name of the exercise, e.g. "Bench Press", "Squat", etc.');
            $table->text('description')->nullable()->comment('A detailed description of how to perform the exercise, including proper form and technique.');
            $table->string('video_url')->nullable()->comment('A URL to a video demonstrating the exercise, if available.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
