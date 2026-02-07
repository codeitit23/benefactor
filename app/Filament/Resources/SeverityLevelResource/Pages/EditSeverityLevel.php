<?php

namespace App\Filament\Resources\SeverityLevelResource\Pages;

use App\Filament\Resources\SeverityLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeverityLevel extends EditRecord
{
    protected static string $resource = SeverityLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
