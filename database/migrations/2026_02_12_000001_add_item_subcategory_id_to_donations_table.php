<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->foreignId('item_subcategory_id')->nullable()->after('item_type_id')->constrained('item_subcategories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropForeign(['item_subcategory_id']);
            $table->dropColumn('item_subcategory_id');
        });
    }
};