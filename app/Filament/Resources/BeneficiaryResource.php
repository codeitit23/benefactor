<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeneficiaryResource\Pages;
use App\Filament\Resources\BeneficiaryResource\RelationManagers;
use App\Models\Beneficiary;
use App\Models\SeverityLevel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BeneficiaryResource extends Resource
{
    protected static ?string $model = Beneficiary::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationLabel = 'Beneficiaries';

    protected static ?string $modelLabel = 'Beneficiary';

    protected static ?string $pluralModelLabel = 'Beneficiaries';

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

                Forms\Components\TextInput::make('phone')
                    ->label('Phone Number')
                    ->tel()
                    ->required()
                    ->maxLength(20),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255),

                Forms\Components\Textarea::make('address')
                    ->label('Address')
                    ->required()
                    ->maxLength(1000),

                Forms\Components\Select::make('need_types')
                    ->label('Need Types')
                    ->multiple()
                    ->relationship('needTypes', 'name')
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('status')
                    ->label('Status')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('severity_level_id')
                    ->label('Level of Severity')
                    ->options(SeverityLevel::pluck('name', 'id'))
                    ->required(),
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

                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('address')
                    ->label('Address')
                    ->limit(50),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->sortable(),

                Tables\Columns\TextColumn::make('severityLevel.name')
                    ->label('Severity Level')
                    ->sortable(),

                Tables\Columns\TextColumn::make('needTypes.name')
                    ->label('Need Types')
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('donations_count')
                    ->label('Donations Received')
                    ->counts('donations')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),

                Tables\Columns\IconColumn::make('has_donations')
                    ->label('Has Donations')
                    ->getStateUsing(fn ($record) => $record->donations()->exists())
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_donations')
                    ->label('View Donations')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn ($record) => DonationResource::getUrl('index') . '?tableFilters[beneficiary_id][value]=' . $record->id, shouldOpenInNewTab: true),
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
            'index' => Pages\ListBeneficiaries::route('/'),
            'create' => Pages\CreateBeneficiary::route('/create'),
            'edit' => Pages\EditBeneficiary::route('/{record}/edit'),
        ];
    }
}
