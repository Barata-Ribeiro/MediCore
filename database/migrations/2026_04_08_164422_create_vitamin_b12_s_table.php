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
        Schema::create('vitamin_b12_s', function (Blueprint $table) {
            $table->id();
            $table->double('vitamin_b12_level')->comment('Vitamin B12 level');
            $table->date('report_date')->comment('Date of the Vitamin B12 report');
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
        Schema::dropIfExists('vitamin_b12_s');
    }
};
