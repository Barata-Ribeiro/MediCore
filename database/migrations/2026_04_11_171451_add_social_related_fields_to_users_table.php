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
        Schema::table('users', function (Blueprint $table) {
            $table->string('provider_id')->nullable()->after('remember_token');
            $table->string('provider_name')->nullable()->after('provider_id');
            $table->string('registration_domain')->nullable()->after('provider_name');
            $table->longText('provider_token')->nullable()->after('registration_domain');
            $table->longText('provider_refresh_token')->nullable()->after('provider_token');
            $table->string('avatar')->nullable()->after('provider_refresh_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'provider_id',
                'provider_name',
                'registration_domain',
                'provider_token',
                'provider_refresh_token',
                'avatar',
            ]);
        });
    }
};
