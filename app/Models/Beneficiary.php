<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Beneficiary extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'beneficiary_status_id',
        'severity_level_id',
    ];

    public function needTypes(): BelongsToMany
    {
        return $this->belongsToMany(NeedType::class, 'beneficiaries_need_types');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(BeneficiaryStatus::class, 'beneficiary_status_id');
    }

    public function severityLevel(): BelongsTo
    {
        return $this->belongsTo(SeverityLevel::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function getDonationsCountAttribute()
    {
        return $this->donations()->count();
    }
}
