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
        Schema::create('ultrasensitive_tshes', function (Blueprint $table) {
            $table->id();
            $table->double('tsh_level')->comment('Ultrasensitive TSH level');
            $table->date('report_date')->comment('Date of the Urea and Creatinine report');
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
        Schema::dropIfExists('ultrasensitive_tshes');
    }
};
