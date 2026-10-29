<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonationContributor extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $contributor): void {
            $contributor->syncDonationAmount();
        });

        static::deleted(function (self $contributor): void {
            $contributor->syncDonationAmount();
        });
    }

    private function syncDonationAmount(): void
    {
        $donation = $this->donation;

        if ($donation) {
            $donation->updateQuietly(['amount' => $donation->contributors()->sum('amount')]);
        }
    }

    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
