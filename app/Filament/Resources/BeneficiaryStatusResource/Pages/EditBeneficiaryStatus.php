<?php

namespace App\Filament\Resources\BeneficiaryStatusResource\Pages;

use App\Filament\Resources\BeneficiaryStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBeneficiaryStatus extends EditRecord
{
    protected static string $resource = BeneficiaryStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
