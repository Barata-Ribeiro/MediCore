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
        Schema::create('glucoses', function (Blueprint $table) {
            $table->id();
            $table->double('glucose_level');
            $table->double('glycated_hemoglobin')->comment('Glycated Hemoglobin (HbA1c)');
            $table->double('estimated_average_glucose')->comment('Estimated Average Glucose (eAG)');
            $table->date('report_date')->comment('Date of the glucose report');
            $table->timestamps();

            $table->foreignId('medical_file_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->index('medical_file_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('glucoses');
    }
};
