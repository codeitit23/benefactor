<?php

namespace App\Filament\Resources\NeedTypeResource\Pages;

use App\Filament\Resources\NeedTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNeedType extends EditRecord
{
    protected static string $resource = NeedTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
