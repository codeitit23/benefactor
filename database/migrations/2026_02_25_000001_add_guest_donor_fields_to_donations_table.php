<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('donor_name')->nullable()->after('user_id');
            $table->string('donor_phone')->nullable()->after('donor_name');
            $table->string('donor_address')->nullable()->after('donor_phone');
        });

        // Make user_id nullable for guest donations.
        DB::statement('ALTER TABLE donations MODIFY user_id BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        // Revert user_id to NOT NULL (may fail if NULL values exist).
        DB::statement('ALTER TABLE donations MODIFY user_id BIGINT UNSIGNED NOT NULL');

        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['donor_name', 'donor_phone', 'donor_address']);
        });
    }
};
