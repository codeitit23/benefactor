<?php

namespace App\Models;

use App\Mail\AdminDonationCreated;
use App\Mail\DonationStatusChanged;
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
                    ->filter(fn ($email) => is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL))
                    ->unique()
                    ->values()
                    ->all();

                $fallbackEmail = env('ADMIN_NOTIFICATION_EMAIL');
                if (is_string($fallbackEmail) && filter_var($fallbackEmail, FILTER_VALIDATE_EMAIL)) {
                    $adminEmails[] = $fallbackEmail;
                }

                $adminEmails = array_values(array_unique($adminEmails));

                if (empty($adminEmails)) {
                    Log::warning('No admin notification email recipients found for donation.', [
                        'donation_id' => $donation->id,
                    ]);

                    return;
                }

                foreach ($adminEmails as $recipientEmail) {
                    Mail::to($recipientEmail)->send(new AdminDonationCreated($donation));

                    Log::info('Admin donation notification sent.', [
                        'donation_id' => $donation->id,
                        'recipient' => $recipientEmail,
                    ]);
                }
            } catch (\Throwable $exception) {
                Log::error('Failed to send admin donation notification email.', [
                    'donation_id' => $donation->id,
                    'error' => $exception->getMessage(),
                ]);
            }
        });

        static::updated(function (self $donation): void {
            if (! $donation->wasChanged('current_status')) {
                return;
            }

            try {
                $donorEmail = $donation->user?->email;
                $currentAdminId = auth()->id();
                $recipientEmails = [];

                if (is_string($donorEmail) && filter_var($donorEmail, FILTER_VALIDATE_EMAIL)) {
                    $recipientEmails[] = $donorEmail;
                }

                $adminEmails = User::query()
                    ->where('role', 'admin')
                    ->when($currentAdminId, fn ($query) => $query->whereKeyNot($currentAdminId))
                    ->whereNotNull('email')
                    ->pluck('email')
                    ->filter(fn ($email) => is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL))
                    ->all();

                $fallbackEmail = env('ADMIN_NOTIFICATION_EMAIL');
                if (is_string($fallbackEmail) && filter_var($fallbackEmail, FILTER_VALIDATE_EMAIL)) {
                    $adminEmails[] = $fallbackEmail;
                }

                $recipientEmails = array_values(array_unique(array_merge($recipientEmails, $adminEmails)));

                foreach ($recipientEmails as $recipientEmail) {
                    Mail::to($recipientEmail)->send(new DonationStatusChanged(
                        $donation,
                        $donation->getOriginal('current_status'),
                        $donation->current_status,
                    ));
                }

                Log::info('Donation status change notifications sent.', [
                    'donation_id' => $donation->id,
                    'recipients' => $recipientEmails,
                    'old_status' => $donation->getOriginal('current_status'),
                    'new_status' => $donation->current_status,
                ]);
            } catch (\Throwable $exception) {
                Log::error('Failed to send donation status change notifications.', [
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
