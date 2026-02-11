<?php

namespace App\Filament\Resources\ItemSubcategoryResource\Pages;

use App\Filament\Resources\ItemSubcategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemSubcategory extends EditRecord
{
    protected static string $resource = ItemSubcategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
