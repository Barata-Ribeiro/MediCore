<?php

use App\Enums\BloodType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private bool $isValidSql;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->isValidSql = in_array(DB::getDriverName(), ['mysql', 'pgsql'], true);

        Schema::create('medical_files', function (Blueprint $table) {
            $table->id();
            $table->enum('blood_type', array_column(BloodType::cases(), 'value'))->nullable();
            $table->string('allergies')->nullable();
            $table->string('diseases')->nullable();
            $table->string('medications')->nullable();
            $table->double('weight')->nullable();
            $table->double('height')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone_number')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->timestamps();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->index('user_id');
            $table->index('blood_type');

            if ($this->isValidSql) {
                $table->fullText(['allergies', 'diseases', 'medications'], 'medical_files_fulltext_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_files');
    }
};
