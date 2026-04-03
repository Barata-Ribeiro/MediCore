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
        Schema::create('complete_blood_counts', function (Blueprint $table) {
            $table->id();
            $table->double('hematocrit')->comment('Hematocrit');
            $table->double('hemoglobin')->comment('Hemoglobin');
            $table->double('red_blood_cell_count')->comment('Red Blood Cell Count');
            $table->double('mean_corpuscular_volume')->comment('Mean Corpuscular Volume');
            $table->double('mean_corpuscular_hemoglobin')->comment('Mean Corpuscular Hemoglobin');
            $table->double('mean_corpuscular_hemoglobin_concentration')->comment('Mean Corpuscular Hemoglobin Concentration');
            $table->double('red_blood_cell_distribution_width')->comment('Red Blood Cell Distribution Width');
            $table->double('leukocyte_count')->comment('Leukocyte Count');
            $table->double('rod_neutrophil_count')->comment('Rod Neutrophil Count');
            $table->double('segmented_neutrophil_count')->comment('Segmented Neutrophil Count');
            $table->double('lymphocyte_count')->comment('Lymphocyte Count');
            $table->double('monocyte_count')->comment('Monocyte Count');
            $table->double('eosinophil_count')->comment('Eosinophil Count');
            $table->double('basophil_count')->comment('Basophil Count');
            $table->double('metamyelocyte_count')->comment('Metamyelocyte Count');
            $table->double('promyelocyte_count')->comment('Promyelocyte Count');
            $table->double('atypical_cell_count')->comment('Atypical Cell Count');
            $table->double('platelet_count')->comment('Platelet Count');
            $table->date('report_date')->comment('Date of the complete blood count report');
            $table->timestamps();

            $table->foreignId('medical_file_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->index('medical_file_id');
            $table->index(['report_date', 'medical_file_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complete_blood_counts');
    }
};
