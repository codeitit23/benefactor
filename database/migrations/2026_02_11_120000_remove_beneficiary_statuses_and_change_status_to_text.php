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
        // add text status to beneficiaries and remove foreign key/column
        Schema::table('beneficiaries', function (Blueprint $table) {
            if (! Schema::hasColumn('beneficiaries', 'status')) {
                $table->text('status')->nullable()->after('address');
            }

            if (Schema::hasColumn('beneficiaries', 'beneficiary_status_id')) {
                $table->dropForeign(['beneficiary_status_id']);
                $table->dropColumn('beneficiary_status_id');
            }
        });

        // remove the beneficiary_statuses lookup table
        Schema::dropIfExists('beneficiary_statuses');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // recreate beneficiary_statuses
        Schema::create('beneficiary_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // restore foreign id on beneficiaries and remove status text column
        Schema::table('beneficiaries', function (Blueprint $table) {
            if (! Schema::hasColumn('beneficiaries', 'beneficiary_status_id')) {
                $table->foreignId('beneficiary_status_id')->constrained()->onDelete('cascade')->after('address');
            }

            if (Schema::hasColumn('beneficiaries', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
