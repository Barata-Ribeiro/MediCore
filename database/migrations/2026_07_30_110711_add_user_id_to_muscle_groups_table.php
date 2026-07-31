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
        Schema::table('muscle_groups', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->dropUnique('muscle_groups_name_unique');
            $table->unique(['user_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('muscle_groups', function (Blueprint $table) {
            $table->dropUnique('muscle_groups_user_id_name_unique');
            $table->unique('name');
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
