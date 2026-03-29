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
        Schema::create('lipid_profiles', function (Blueprint $table) {
            $table->id();
            $table->double('total_cholesterol')->comment('Total Cholesterol');
            $table->double('hdl_cholesterol')->comment('High-Density Lipoprotein Cholesterol');
            $table->double('ldl_cholesterol')->comment('Low-Density Lipoprotein Cholesterol');
            $table->double('vldl_cholesterol')->comment('Very Low-Density Lipoprotein Cholesterol');
            $table->double('triglycerides')->comment('Triglycerides');
            $table->date('report_date')->comment('Date of the lipid profile report');
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
        Schema::dropIfExists('lipid_profiles');
    }
};
