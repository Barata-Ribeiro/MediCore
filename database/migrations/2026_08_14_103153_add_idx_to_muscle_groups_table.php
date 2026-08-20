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

        Schema::table('muscle_groups', function (Blueprint $table) {
            $table->index(['name', 'user_id']);

            if ($this->isValidSql) {
                $table->fullText('name', 'muscle_groups_fulltext_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('muscle_groups', function (Blueprint $table) {
            $table->dropIndex(['name', 'user_id']);
            $table->dropFullText('muscle_groups_fulltext_index');
        });
    }
};
