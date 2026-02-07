<?php

namespace App\Filament\Resources\BeneficiaryStatusResource\Pages;

use App\Filament\Resources\BeneficiaryStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBeneficiaryStatuses extends ListRecords
{
    protected static string $resource = BeneficiaryStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
