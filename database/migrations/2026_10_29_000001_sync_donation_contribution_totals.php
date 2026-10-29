<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('donations')
            ->whereExists(fn ($query) => $query->selectRaw('1')
                ->from('donation_contributors')
                ->whereColumn('donation_contributors.donation_id', 'donations.id'))
            ->update([
                'amount' => DB::raw('(SELECT SUM(amount) FROM donation_contributors WHERE donation_contributors.donation_id = donations.id)'),
            ]);
    }

    public function down(): void
    {
        // Previously entered amounts cannot be recovered from the calculated totals.
    }
};
