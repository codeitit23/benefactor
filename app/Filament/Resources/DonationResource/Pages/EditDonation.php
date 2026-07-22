<?php

namespace App\Filament\Resources\DonationResource\Pages;

use App\Filament\Resources\DonationResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDonation extends EditRecord
{
    protected static string $resource = DonationResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $userId = $data['user_id'] ?? $this->record->user_id;

        if (blank($userId)) {
            return $data;
        }

        $user = User::find($userId);

        if (! $user) {
            return $data;
        }

        if (blank($data['donor_name'] ?? null)) {
            $data['donor_name'] = $user->name;
        }

        if (blank($data['donor_phone'] ?? null)) {
            $data['donor_phone'] = $user->phone;
        }

        if (blank($data['donor_address'] ?? null)) {
            $data['donor_address'] = $user->address;
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
