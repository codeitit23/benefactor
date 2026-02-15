<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NeedTypeResource\Pages;
use App\Filament\Resources\NeedTypeResource\RelationManagers;
use App\Models\NeedType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NeedTypeResource extends Resource
{
    protected static ?string $model = NeedType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Beneficiary Settings';

    protected static ?string $modelLabel = 'Need Type';

    protected static ?string $pluralModelLabel = 'Need Types';

       public static function canViewAny(): bool
    {
        return auth()->user()?->isAdmin();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNeedTypes::route('/'),
            'create' => Pages\CreateNeedType::route('/create'),
            'edit' => Pages\EditNeedType::route('/{record}/edit'),
        ];
    }
}
