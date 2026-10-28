<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('donations', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Revert user_id to NOT NULL (may fail if NULL values exist).
        Schema::table('donations', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });

        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['donor_name', 'donor_phone', 'donor_address']);
        });
    }
};
