<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomepageSettingResource\Pages;
use App\Models\HomepageSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HomepageSettingResource extends Resource
{
    protected static ?string $model = HomepageSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'اعدادات عامة';

    protected static ?string $modelLabel = 'اعداد الصفحة الرئيسية';

    protected static ?string $pluralModelLabel = 'اعدادات الصفحة الرئيسية';

    public static function canViewAny(): bool
    {
        return auth()->user()?->isAdmin();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('logo_path')
                    ->label('شعار المنصة')
                    ->image()
                    ->disk('public')
                    ->directory('homepage/logo')
                    ->visibility('public')
                    ->nullable(),

                Forms\Components\FileUpload::make('cover_path')
                    ->label('صورة الغلاف')
                    ->image()
                    ->disk('public')
                    ->directory('homepage/cover')
                    ->visibility('public')
                    ->nullable()
                    ->helperText('تظهر كخلفية للصفحة الرئيسية.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('الشعار')
                    ->disk('public'),

                Tables\Columns\ImageColumn::make('cover_path')
                    ->label('الغلاف')
                    ->disk('public'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('اخر تحديث')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomepageSettings::route('/'),
            'create' => Pages\CreateHomepageSetting::route('/create'),
            'edit' => Pages\EditHomepageSetting::route('/{record}/edit'),
        ];
    }
}
