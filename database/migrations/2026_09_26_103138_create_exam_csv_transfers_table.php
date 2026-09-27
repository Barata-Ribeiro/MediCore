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
        Schema::create('exam_csv_transfers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medical_file_id')->constrained()->cascadeOnDelete();
            $table->string('exam_type');
            $table->string('direction');
            $table->string('status');
            $table->string('path');
            $table->json('headers');
            $table->string('delimiter', 1)->default(',');
            $table->unsignedBigInteger('offset')->default(0);
            $table->unsignedBigInteger('cursor')->default(0);
            $table->unsignedBigInteger('max_id')->default(0);
            $table->unsignedInteger('revision')->default(0);
            $table->unsignedInteger('processed')->default(0);
            $table->unsignedInteger('validated_rows')->default(0);
            $table->string('error_code')->nullable();
            $table->unsignedInteger('error_line')->nullable();
            $table->timestamp('expires_at')->index();
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_csv_transfers');
    }
};
