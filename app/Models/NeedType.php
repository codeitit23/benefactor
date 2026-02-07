<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NeedType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function beneficiaries(): BelongsToMany
    {
        return $this->belongsToMany(Beneficiary::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
