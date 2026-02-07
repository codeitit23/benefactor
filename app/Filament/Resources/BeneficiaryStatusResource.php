<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeneficiaryStatusResource\Pages;
use App\Filament\Resources\BeneficiaryStatusResource\RelationManagers;
use App\Models\BeneficiaryStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BeneficiaryStatusResource extends Resource
{
    protected static ?string $model = BeneficiaryStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Beneficiary Settings';

    protected static ?string $modelLabel = 'Beneficiary Status';

    protected static ?string $pluralModelLabel = 'Beneficiary Statuses';

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
            'index' => Pages\ListBeneficiaryStatuses::route('/'),
            'create' => Pages\CreateBeneficiaryStatus::route('/create'),
            'edit' => Pages\EditBeneficiaryStatus::route('/{record}/edit'),
        ];
    }
}
