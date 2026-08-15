<?php

namespace App\Models;

use App\Mail\AdminDonationCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Donation extends Model
{
    protected $fillable = [
        'donation_number',
        'user_id',
        'donor_name',
        'donor_phone',
        'donor_address',
        'donation_type',
        'item_type_id',
        'item_subcategory_id',
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
        'beneficiary_id',
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
                $nextId = (static::max('id') ?? 0) + 1;
                $donation->donation_number = 'F_' . $nextId;
            }
        });

        static::created(function (self $donation): void {
            try {
                $adminEmails = User::query()
                    ->where('role', 'admin')
                    ->whereNotNull('email')
                    ->pluck('email')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                if (empty($adminEmails)) {
                    return;
                }

                Mail::to($adminEmails)->send(new AdminDonationCreated($donation));
            } catch (\Throwable $exception) {
                Log::error('Failed to send admin donation notification email.', [
                    'donation_id' => $donation->id,
                    'error' => $exception->getMessage(),
                ]);
            }
        });
    }

    public function getDonorNameAttribute($value): ?string
    {
        if (!empty($value)) {
            return $value;
        }

        return $this->user?->name;
    }

    public function getDonorPhoneAttribute($value): ?string
    {
        if (!empty($value)) {
            return $value;
        }

        return $this->user?->phone;
    }

    public function getDonorAddressAttribute($value): ?string
    {
        if (!empty($value)) {
            return $value;
        }

        return $this->user?->address;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function itemType(): BelongsTo
    {
        return $this->belongsTo(ItemType::class);
    }

    public function itemSubcategory(): BelongsTo
    {
        return $this->belongsTo(ItemSubcategory::class);
    }

    public function itemStatus(): BelongsTo
    {
        return $this->belongsTo(ItemStatus::class);
    }

    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
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
