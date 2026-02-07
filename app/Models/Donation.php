<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Donation extends Model
{
    protected $fillable = [
        'donation_number',
        'user_id',
        'donation_type',
        'item_type_id',
        'item_status_id',
        'payment_method',
        'amount',
        'item_images',
        'item_video',
        'pickup_date',
        'notes',
        'current_status',
        'status_note',
        'beneficiary_images',
        'beneficiary_video',
    ];

    protected $casts = [
        'item_images' => 'array',
        'beneficiary_images' => 'array',
        'pickup_date' => 'date',
        'amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($donation) {
            if (empty($donation->donation_number)) {
                $donation->donation_number = 'DON-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function itemType(): BelongsTo
    {
        return $this->belongsTo(ItemType::class);
    }

    public function itemStatus(): BelongsTo
    {
        return $this->belongsTo(ItemStatus::class);
    }

    public function scopePending($query)
    {
        return $query->where('current_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('current_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('current_status', 'rejected');
    }

    public function scopeCompleted($query)
    {
        return $query->where('current_status', 'completed');
    }

    public function isCashDonation(): bool
    {
        return $this->donation_type === 'cash';
    }

    public function isItemDonation(): bool
    {
        return $this->donation_type === 'item';
    }
}
