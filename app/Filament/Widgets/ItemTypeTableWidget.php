<?php

namespace App\Filament\Widgets;

use App\Models\Donation;
use App\Models\ItemType;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ItemTypeTableWidget extends BaseWidget
{
    protected static ?string $heading = 'Item Types and Donor Counts';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ItemType::active()
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Item Type')
                    ->sortable(),

                Tables\Columns\TextColumn::make('donor_count')
                    ->label('Number of Donors')
                    ->getStateUsing(function (ItemType $record) {
                        return Donation::where('item_type_id', $record->id)->distinct('user_id')->count('user_id');
                    }),
            ])
            ->defaultSort('name');
    }
}
