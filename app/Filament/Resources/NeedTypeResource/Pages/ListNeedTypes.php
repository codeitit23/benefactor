<?php

namespace App\Filament\Resources\NeedTypeResource\Pages;

use App\Filament\Resources\NeedTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNeedTypes extends ListRecords
{
    protected static string $resource = NeedTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
