<?php

namespace App\Filament\Widgets;

use App\Models\Donation;
use App\Models\ItemType;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ItemTypeTableWidget extends BaseWidget
{
    protected static ?string $heading = 'انواع العناصر وعدد المتبرعين';

    public function table(Table $table): Table
    {
        $user = auth()->user();

        $query = ItemType::active();
        if (! $user?->isAdmin()) {
            $query = $query->whereHas('donations', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        return $table
            ->query(
                $query
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('نوع العنصر')
                    ->sortable(),

                Tables\Columns\TextColumn::make('donor_count')
                    ->label('عدد المتبرعين')
                    ->getStateUsing(function (ItemType $record) {
                        $user = auth()->user();
                        if ($user?->isAdmin()) {
                            return Donation::where('item_type_id', $record->id)->distinct('user_id')->count('user_id');
                        }

                        return Donation::where('item_type_id', $record->id)
                            ->where('user_id', $user->id)
                            ->count();
                    }),
            ])
            ->defaultSort('name');
    }
}
