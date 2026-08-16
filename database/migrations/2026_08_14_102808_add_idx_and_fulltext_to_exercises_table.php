<?php

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

        Schema::table('exercises', function (Blueprint $table) {
            $table->index('name');
            $table->index(['name', 'user_id']);

            if ($this->isValidSql) {
                $table->fullText(['name', 'description', 'video_url'], 'exercises_fulltext_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['name', 'user_id']);
            $table->dropFullText('exercises_fulltext_index');
        });
    }
};
