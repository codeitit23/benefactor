<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemSubcategoryResource\Pages;
use App\Filament\Resources\ItemSubcategoryResource\RelationManagers;
use App\Models\ItemSubcategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemSubcategoryResource extends Resource
{
    protected static ?string $model = ItemSubcategory::class;

    protected static ?string $navigationGroup = 'اعدادات التبرعات';

    protected static ?string $navigationLabel = 'الفئات الفرعية للعناصر';

    protected static ?string $modelLabel = 'فئة فرعية للعنصر';

    protected static ?string $pluralModelLabel = 'الفئات الفرعية للعناصر';

    protected static ?int $navigationSort = 3;

    protected static bool $shouldRegisterNavigation = false;

    public static function canViewAny(): bool
    {
        return auth()->user()?->isAdmin();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('item_type_id')
                    ->relationship('itemType', 'name')
                    ->label('نوع العنصر')
                    ->required()
                    ->searchable(),

                Forms\Components\TextInput::make('name')
                    ->label('الاسم')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Toggle::make('is_active')
                    ->label('مفعل')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('itemType.name')
                    ->label('نوع العنصر')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('مفعل'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الانشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('item_type_id')
                    ->relationship('itemType', 'name')
                    ->label('نوع العنصر'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('الحالة')
                    ->boolean()
                    ->trueLabel('المفعل فقط')
                    ->falseLabel('غير المفعل فقط')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),
                Tables\Actions\Action::make('toggle_active')
                    ->label(fn ($record) => $record->is_active ? 'تعطيل' : 'تفعيل')
                    ->icon(fn ($record) => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn ($record) => $record->is_active ? 'danger' : 'success')
                    ->action(function ($record) {
                        $record->update(['is_active' => !$record->is_active]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('تغيير حالة الفئة الفرعية')
                    ->modalDescription('هل انت متأكد انك تريد تغيير حالة الفئة الفرعية؟'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد')
                        ->requiresConfirmation(),
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
            'index' => Pages\ListItemSubcategories::route('/'),
            'create' => Pages\CreateItemSubcategory::route('/create'),
            'edit' => Pages\EditItemSubcategory::route('/{record}/edit'),
        ];
    }
}
