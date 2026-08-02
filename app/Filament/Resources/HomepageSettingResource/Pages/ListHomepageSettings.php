<?php

namespace App\Filament\Resources\HomepageSettingResource\Pages;

use App\Filament\Resources\HomepageSettingResource;
use App\Models\HomepageSetting;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHomepageSettings extends ListRecords
{
    protected static string $resource = HomepageSettingResource::class;

    protected function getHeaderActions(): array
    {
        if (HomepageSetting::query()->exists()) {
            return [];
        }

        return [
            Actions\CreateAction::make()->label('اضافة اعدادات'),
        ];
    }
}
