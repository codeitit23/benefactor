<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Donation;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $donationId = request()->query('donation_id');
        $phone = $this->record->phone;

        if ($donationId) {
            Donation::whereKey($donationId)->update(['user_id' => $this->record->id]);
        }

        if ($phone) {
            Donation::whereNull('user_id')
                ->where('donor_phone', $phone)
                ->update(['user_id' => $this->record->id]);
        }
    }
}
