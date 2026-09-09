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
        Schema::create('tgo_and_tgps', function (Blueprint $table) {
            $table->id();
            $table->double('tgo_level')->comment('Aspartate aminotransferase (TGO/AST) in U/L');
            $table->double('tgp_level')->comment('Alanine aminotransferase (TGP/ALT) in U/L');
            $table->date('report_date')->comment('Date of the TGO and TGP report');
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
        Schema::dropIfExists('tgo_and_tgps');
    }
};
