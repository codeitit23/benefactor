<?php

namespace App\Filament\Resources\ItemSubcategoryResource\Pages;

use App\Filament\Resources\ItemSubcategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemSubcategories extends ListRecords
{
    protected static string $resource = ItemSubcategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
