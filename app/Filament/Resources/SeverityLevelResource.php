<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeverityLevelResource\Pages;
use App\Filament\Resources\SeverityLevelResource\RelationManagers;
use App\Models\SeverityLevel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeverityLevelResource extends Resource
{
    protected static ?string $model = SeverityLevel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Beneficiary Settings';

    protected static ?string $modelLabel = 'Severity Level';

    protected static ?string $pluralModelLabel = 'Severity Levels';

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
            'index' => Pages\ListSeverityLevels::route('/'),
            'create' => Pages\CreateSeverityLevel::route('/create'),
            'edit' => Pages\EditSeverityLevel::route('/{record}/edit'),
        ];
    }
}
