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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('bio', 500)->nullable();
            $table->date('birth_date');
            $table->string('phone_number');
            $table->string('address');
            $table->enum('sex', ['male', 'female'])->nullable();
            $table->string('gender_identity')->nullable();
            $table->timestamps();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->index('user_id');
            $table->index(['first_name', 'last_name']);
            $table->fullText(['first_name', 'last_name', 'bio', 'address'], 'profiles_fulltext_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
