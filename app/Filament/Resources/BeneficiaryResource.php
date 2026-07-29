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

    protected static ?string $navigationLabel = 'المستفيدون';

    protected static ?string $modelLabel = 'مستفيد';

    protected static ?string $pluralModelLabel = 'المستفيدون';

       public static function canViewAny(): bool
    {
        return auth()->user()?->isAdmin();
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('الاسم')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->tel()
                    ->required()
                    ->placeholder('مثال: 70123456')
                    ->minLength(8)
                    ->maxLength(8)
                    ->rule('regex:/^\\d{8}$/'),

                Forms\Components\TextInput::make('email')
                    ->label('البريد الالكتروني')
                    ->email()
                    ->maxLength(255),

                Forms\Components\Textarea::make('address')
                    ->label('العنوان')
                    ->required()
                    ->maxLength(1000),

                Forms\Components\Select::make('need_types')
                    ->label('انواع الاحتياج')
                    ->multiple()
                    ->relationship('needTypes', 'name')
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('status')
                    ->label('الحالة')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('severity_level_id')
                    ->label('مستوى الشدة')
                    ->options(SeverityLevel::pluck('name', 'id'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('الهاتف')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الالكتروني')
                    ->searchable(),

                Tables\Columns\TextColumn::make('address')
                    ->label('العنوان')
                    ->limit(50),

                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->sortable(),

                Tables\Columns\TextColumn::make('severityLevel.name')
                    ->label('مستوى الشدة')
                    ->sortable(),

                Tables\Columns\TextColumn::make('needTypes.name')
                    ->label('انواع الاحتياج')
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('donations_count')
                    ->label('التبرعات المستلمة')
                    ->counts('donations')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),

                Tables\Columns\IconColumn::make('has_donations')
                    ->label('لديه تبرعات')
                    ->getStateUsing(fn ($record) => $record->donations()->exists())
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\Filter::make('name')
                    ->label('الاسم')
                    ->form([
                        Forms\Components\TextInput::make('value')
                            ->label('الاسم'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['value'] ?? null,
                        fn (Builder $query, string $value): Builder => $query->where('name', 'like', "%{$value}%")
                    )),

                Tables\Filters\Filter::make('phone')
                    ->label('الهاتف')
                    ->form([
                        Forms\Components\TextInput::make('value')
                            ->label('الهاتف'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['value'] ?? null,
                        fn (Builder $query, string $value): Builder => $query->where('phone', 'like', "%{$value}%")
                    )),

                Tables\Filters\Filter::make('email')
                    ->label('البريد الالكتروني')
                    ->form([
                        Forms\Components\TextInput::make('value')
                            ->label('البريد الالكتروني'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['value'] ?? null,
                        fn (Builder $query, string $value): Builder => $query->where('email', 'like', "%{$value}%")
                    )),

                Tables\Filters\Filter::make('address')
                    ->label('العنوان')
                    ->form([
                        Forms\Components\TextInput::make('value')
                            ->label('العنوان'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['value'] ?? null,
                        fn (Builder $query, string $value): Builder => $query->where('address', 'like', "%{$value}%")
                    )),

                Tables\Filters\Filter::make('status')
                    ->label('الحالة')
                    ->form([
                        Forms\Components\TextInput::make('value')
                            ->label('الحالة'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['value'] ?? null,
                        fn (Builder $query, string $value): Builder => $query->where('status', 'like', "%{$value}%")
                    )),

                Tables\Filters\SelectFilter::make('severity_level_id')
                    ->label('مستوى الشدة')
                    ->relationship('severityLevel', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('need_types')
                    ->label('انواع الاحتياج')
                    ->relationship('needTypes', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('donations_count')
                    ->label('عدد التبرعات')
                    ->form([
                        Forms\Components\TextInput::make('min')
                            ->label('الحد الادنى')
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\TextInput::make('max')
                            ->label('الحد الاقصى')
                            ->numeric()
                            ->minValue(0),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $min = $data['min'] ?? null;
                        $max = $data['max'] ?? null;

                        if (blank($min) && blank($max)) {
                            return $query;
                        }

                        $query->withCount('donations');

                        if (filled($min)) {
                            $query->having('donations_count', '>=', (int) $min);
                        }

                        if (filled($max)) {
                            $query->having('donations_count', '<=', (int) $max);
                        }

                        return $query;
                    }),

                Tables\Filters\TernaryFilter::make('has_donations')
                    ->label('لديه تبرعات')
                    ->queries(
                        true: fn (Builder $query) => $query->has('donations'),
                        false: fn (Builder $query) => $query->doesntHave('donations'),
                        blank: fn (Builder $query) => $query,
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_donations')
                    ->label('عرض التبرعات')
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
