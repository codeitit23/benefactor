<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Filament\Resources\DonationResource\RelationManagers;
use App\Filament\Resources\UserResource;
use App\Models\Donation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationLabel = 'التبرعات';

    protected static ?string $modelLabel = 'تبرع';

    protected static ?string $pluralModelLabel = 'التبرعات';

    public static function canViewAny(): bool
    {
        return auth()->check();
    }

    public static function canCreate(): bool
    {
        return auth()->check();
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return true;
        }

        $inProgressStatuses = ['pending', 'in_progress'];

        return $record->user_id === $user->id
            && in_array($record->current_status, $inProgressStatuses, true);
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->isAdmin();
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();
        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات التبرع')
                    ->schema([
                        Forms\Components\TextInput::make('donation_number')
                            ->label('رقم التبرع')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('يتم توليده تلقائيا'),

                        Forms\Components\Hidden::make('user_id')
                            ->default(fn () => auth()->id())
                            ->required()
                            ->visible(fn () => !auth()->user()?->isAdmin()),

                        Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label('المتبرع')
                                    ->options(\App\Models\User::where('active', true)->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required(fn () => !auth()->user()?->isAdmin())
                                    ->nullable()
//                                    ->helperText('Ø§ØªØ±ÙƒÙ‡ ÙØ§Ø±ØºØ§ Ù„Ù„ØªØ¨Ø±Ø¹ ÙƒØ¶ÙŠÙ')
                                    ->visible(fn () => auth()->user()?->isAdmin())
                                    ->default(fn () => auth()->user()?->isAdmin() ? null : auth()->id()),

                                Forms\Components\Select::make('donation_type')
                                    ->label('نوع التبرع')
                                    ->options([
                                        'item' => 'تبرع عيني',
                                        'cash' => 'تبرع نقدي',
                                    ])
                                    ->default('item')
                                    ->live()
                                    ->required(),


                                Forms\Components\Select::make('item_type_id')
                                    ->label('نوع العنصر')
                                    ->options(\App\Models\ItemType::active()->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->visible(fn (Forms\Get $get) => $get('donation_type') === 'item'),

                                Forms\Components\Select::make('item_subcategory_id')
                                    ->label('الفئة الفرعية للعنصر')
                                    ->options(function (callable $get) {
                                        $typeId = $get('item_type_id');
                                        if (!$typeId) return [];
                                        return \App\Models\ItemSubcategory::where('item_type_id', $typeId)
                                            ->active()
                                            ->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->visible(fn (Forms\Get $get) => $get('donation_type') === 'item' && $get('item_type_id')),

                                Forms\Components\Select::make('item_status_id')
                                    ->label('حالة العنصر')
                                    ->options(\App\Models\ItemStatus::active()->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->visible(fn (Forms\Get $get) => $get('donation_type') === 'item'),

                                // Cash donation fields
                                Forms\Components\Select::make('payment_method')
                                    ->options([
                                        'cash' => 'نقدا',
                                        'wish' => 'ويش',
                                        'omt' => 'OMT',
                                        'credit_card' => 'بطاقة ائتمان',
                                    ])
                                    ->required()
                                    ->visible(fn (Forms\Get $get) => $get('donation_type') === 'cash'),

                                Forms\Components\TextInput::make('amount')
                                    ->label('المبلغ')
                                    ->numeric()
                                    ->prefix('USD')
                                    ->minValue(0)
                                    ->required()
                                    ->visible(fn (Forms\Get $get) => $get('donation_type') === 'cash'),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('donor_name')
                                    ->label('اسم المتبرع')
                                    ->maxLength(255)
                                    ->required(fn (Forms\Get $get) => blank($get('user_id')))
                                    ->visible(fn (Forms\Get $get) => blank($get('user_id')) || auth()->user()?->isAdmin()),

                                Forms\Components\TextInput::make('donor_phone')
                                    ->label('هاتف المتبرع')
                                    ->maxLength(50)
                                    ->rule('regex:/^\d{8}$/')
                                    ->helperText('رقم مكون من 8 أرقام')
                                    ->required(fn (Forms\Get $get) => blank($get('user_id')))
                                    ->visible(fn (Forms\Get $get) => blank($get('user_id')) || auth()->user()?->isAdmin()),

                                Forms\Components\Textarea::make('donor_address')
                                    ->label('عنوان المتبرع')
                                    ->rows(2)
                                    ->maxLength(255)
                                    ->required(fn (Forms\Get $get) => blank($get('user_id')))
                                    ->visible(fn (Forms\Get $get) => blank($get('user_id')) || auth()->user()?->isAdmin()),
                            ]),
                    ]),

                Forms\Components\Section::make('تفاصيل العنصر')
                    ->schema([
                        Forms\Components\FileUpload::make('item_images')
                            ->label('صور العنصر (حد اقصى 5)')
                            ->multiple()
                            ->maxFiles(5)
                            ->image()
                            ->imageEditor()
                            ->directory('donations/items')
                            ->visibility('public')
                            ->visible(fn (Forms\Get $get) => $get('donation_type') === 'item'),

                        Forms\Components\FileUpload::make('item_video')
                            ->label('فيديو العنصر')
                            ->acceptedFileTypes(['video/mp4', 'video/avi', 'video/mov', 'video/wmv'])
                            ->maxSize(51200) // 50MB
                            ->directory('donations/videos')
                            ->visibility('public')
                            ->visible(fn (Forms\Get $get) => $get('donation_type') === 'item'),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('pickup_date')
                                    ->label('تاريخ الاستلام')
                                    ->minDate(now())
                                    ->visible(fn (Forms\Get $get) => $get('donation_type') === 'item'),

                                Forms\Components\Textarea::make('notes')
                                    ->label('ملاحظات')
                                    ->rows(4)
                                    ->maxLength(1000),
                            ]),
                    ])
                    ->visible(fn (Forms\Get $get) => $get('donation_type') === 'item'),

                Forms\Components\Section::make('اجراءات المدير')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('beneficiary_id')
                                    ->label('ربط بمستفيد')
                                    ->options(function () {
                                        return \App\Models\Beneficiary::query()
                                            ->pluck('name', 'id');
                                    })
                                    ->getSearchResultsUsing(function (string $search) {
                                        return \App\Models\Beneficiary::query()
                                            ->where('name', 'like', "%{$search}%")
                                            ->orWhere('phone', 'like', "%{$search}%")
                                            ->limit(10)
                                            ->pluck('name', 'id');
                                    })
                                    ->getOptionLabelUsing(fn ($value) => \App\Models\Beneficiary::find($value)?->name)
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),

                                Forms\Components\Select::make('current_status')
                                    ->options([
                                        'pending' => 'قيد الانتظار',
                                        'approved' => 'معتمد',
                                        'rejected' => 'مرفوض',
                                        'completed' => 'مكتمل',
                                    ])
                                    ->default('pending')
                                    ->required(),

                                Forms\Components\Textarea::make('status_note')
                                    ->label('ملاحظة الحالة')
                                    ->rows(3)
                                    ->maxLength(1000)
                                    ->helperText('اضف ملاحظة تشرح تغيير الحالة، خاصة عند الرفض'),
                            ]),

                        Forms\Components\FileUpload::make('beneficiary_images')
                            ->label('صور المستفيد')
                            ->multiple()
                            ->maxFiles(10)
                            ->image()
                            ->imageEditor()
                            ->directory('donations/beneficiaries')
                            ->visibility('public'),

                        Forms\Components\FileUpload::make('beneficiary_video')
                            ->label('فيديو المستفيد')
                            ->acceptedFileTypes(['video/mp4', 'video/avi', 'video/mov', 'video/wmv'])
                            ->maxSize(51200) // 50MB
                            ->directory('donations/beneficiary-videos')
                            ->visibility('public'),
                    ])
                    ->visible(fn () => auth()->user()?->isAdmin()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('donation_number')
                    ->label('رقم التبرع')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('donor_name')
                    ->label('المتبرع')
                    ->getStateUsing(fn ($record) => $record->donor_name ?? $record->user?->name)
                    ->searchable()
                    ->sortable()
                    ->placeholder('ضيف')
                    ->visible(fn () => auth()->user()?->isAdmin()),

                Tables\Columns\TextColumn::make('donor_phone')
                    ->label('هاتف المتبرع')
                    ->getStateUsing(fn ($record) => $record->donor_phone ?? $record->user?->phone)
                    ->searchable()
                    // ->toggleable(isToggledHiddenByDefault: false)
                    ->placeholder('غير متوفر')
                    ->visible(fn () => auth()->user()?->isAdmin()),

                Tables\Columns\TextColumn::make('beneficiary.name')
                    ->label('تم التبرع لـ')
                    ->searchable()
                    ->sortable()
                    ->placeholder('غير مخصص'),

                Tables\Columns\TextColumn::make('beneficiary.phone')
                    ->label('هاتف المستفيد')
                    ->searchable()
                    // ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('غير مخصص')
                    ->visible(fn () => auth()->user()?->isAdmin()),

                Tables\Columns\TextColumn::make('donation_type')
                    ->label('نوع التبرع')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'cash' => 'تبرع نقدي',
                        'item' => 'تبرع عيني',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'success',
                        'item' => 'info',
                    }),

                Tables\Columns\TextColumn::make('itemType.name')
                    ->label('نوع العنصر')
                    ->visible(fn () => auth()->user()?->isAdmin()),

                Tables\Columns\TextColumn::make('itemStatus.name')
                    ->label('حالة العنصر')
                    ->badge()
                    ->color(fn ($record) => $record->itemStatus?->color ?? 'gray')
                    ->visible(fn () => auth()->user()?->isAdmin()),

                Tables\Columns\TextColumn::make('amount')
                    ->label('المبلغ')
                    ->money('USD')
                    ->visible(fn () => auth()->user()?->isAdmin()),

                Tables\Columns\TextColumn::make('current_status')
                    ->label('الحالة الحالية')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'قيد الانتظار',
                        'approved' => 'معتمد',
                        'rejected' => 'مرفوض',
                        'completed' => 'مكتمل',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'completed' => 'gray',
                    }),

                Tables\Columns\TextColumn::make('pickup_date')
                    ->label('تاريخ الاستلام')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('donor_phone')
                    ->label('هاتف المتبرع')
                    ->form([
                        Forms\Components\TextInput::make('phone')
                            ->label('هاتف المتبرع')
                            ->placeholder('ابحث برقم الهاتف'),
                    ])
                    ->query(function ($query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query->when(
                            $data['phone'] ?? null,
                            fn ($query, $phone): \Illuminate\Database\Eloquent\Builder => $query->whereHas(
                                'user',
                                fn ($q) => $q->where('phone', 'like', "%{$phone}%")
                            )
                        );
                    })
                    ->visible(fn () => auth()->user()?->isAdmin()),

                Tables\Filters\Filter::make('beneficiary_phone')
                    ->label('هاتف المستفيد')
                    ->form([
                        Forms\Components\TextInput::make('phone')
                            ->label('هاتف المستفيد')
                            ->placeholder('ابحث برقم الهاتف'),
                    ])
                    ->query(function ($query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query->when(
                            $data['phone'] ?? null,
                            fn ($query, $phone): \Illuminate\Database\Eloquent\Builder => $query->whereHas(
                                'beneficiary',
                                fn ($q) => $q->where('phone', 'like', "%{$phone}%")
                            )
                        );
                    })
                    ->visible(fn () => auth()->user()?->isAdmin()),
                Tables\Filters\Filter::make('donor_phone')
                    ->label('هاتف المتبرع')
                    ->form([
                        Forms\Components\TextInput::make('phone')
                            ->label('هاتف المتبرع')
                            ->placeholder('هاتف المتبرع'),
                    ])
                    ->query(function ($query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query->when(
                            $data['phone'] ?? null,
                            fn ($query, $phone): \Illuminate\Database\Eloquent\Builder => $query->whereHas(
                                'user',
                                fn ($q) => $q->where('phone', 'like', "%{$phone}%")
                            )
                        );
                    })
                    ->visible(fn () => auth()->user()?->isAdmin()),

                Tables\Filters\Filter::make('beneficiary_phone')
                    ->label('هاتف المستفيد')
                    ->form([
                        Forms\Components\TextInput::make('phone')
                            ->label('هاتف المستفيد')
                            ->placeholder('هاتف المستفيد'),
                    ])
                    ->query(function ($query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query->when(
                            $data['phone'] ?? null,
                            fn ($query, $phone): \Illuminate\Database\Eloquent\Builder => $query->whereHas(
                                'beneficiary',
                                fn ($q) => $q->where('phone', 'like', "%{$phone}%")
                            )
                        );
                    })
                    ->visible(fn () => auth()->user()?->isAdmin()),

                Tables\Filters\SelectFilter::make('beneficiary_id')
                    ->label('المستفيد')
                    ->relationship('beneficiary', 'name')
                    ->preload()
                    ->searchable(),

                Tables\Filters\SelectFilter::make('donation_type')
                    ->options([
                        'item' => 'تبرع عيني',
                        'cash' => 'تبرع نقدي',
                    ]),

                Tables\Filters\SelectFilter::make('current_status')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'approved' => 'معتمد',
                        'rejected' => 'مرفوض',
                        'completed' => 'مكتمل',
                    ]),

                Tables\Filters\SelectFilter::make('item_type_id')
                    ->label('نوع العنصر')
                    ->options(\App\Models\ItemType::active()->pluck('name', 'id')),

                Tables\Filters\Filter::make('pickup_date')
                    ->label('تاريخ الاستلام')
                    ->form([
                        Forms\Components\DatePicker::make('pickup_from')
                            ->label('من تاريخ'),
                        Forms\Components\DatePicker::make('pickup_until')
                            ->label('إلى تاريخ'),
                    ])
                    ->query(function ($query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            ->when(
                                $data['pickup_from'],
                                fn ($query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('pickup_date', '>=', $date),
                            )
                            ->when(
                                $data['pickup_until'],
                                fn ($query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('pickup_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض'),
                Tables\Actions\EditAction::make()
                    ->label('تعديل')
                    ->visible(fn ($record) => auth()->user()?->isAdmin()
                        || in_array($record->current_status, ['pending', 'in_progress'], true)),
                Tables\Actions\Action::make('relate_beneficiary')
                    ->label('ربط المستفيد')
                    ->icon('heroicon-o-user-plus')
                    ->color('primary')
                    ->visible(fn () => auth()->user()?->isAdmin())
                    ->form([
                        Forms\Components\Select::make('beneficiary_id')
                            ->label('اختر المستفيد')
                            ->options(function () {
                                return \App\Models\Beneficiary::query()
                                    ->pluck('name', 'id');
                            })
                            ->getSearchResultsUsing(function (string $search) {
                                return \App\Models\Beneficiary::query()
                                    ->where('name', 'like', "%{$search}%")
                                    ->orWhere('phone', 'like', "%{$search}%")
                                    ->limit(10)
                                    ->pluck('name', 'id');
                            })
                            ->getOptionLabelUsing(fn ($value) => \App\Models\Beneficiary::find($value)?->name)
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('ابحث بالاسم او رقم الهاتف'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update(['beneficiary_id' => $data['beneficiary_id']]);
                    }),
                Tables\Actions\Action::make('create_user')
                    ->label('إضافة مستخدم')
                    ->icon('heroicon-o-user-plus')
                    ->color('warning')
                    ->visible(fn ($record) => auth()->user()?->isAdmin() && blank($record->user_id))
                    ->url(fn ($record) => UserResource::getUrl('create', [
                        'donation_id' => $record->id,
                        'name' => $record->donor_name,
                        'phone' => $record->donor_phone,
                        'address' => $record->donor_address,
                    ])),
                Tables\Actions\Action::make('approve')
                    ->label('اعتماد')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => auth()->user()?->isAdmin() && $record->current_status === 'pending')
                    ->action(function ($record) {
                        $record->update(['current_status' => 'approved']);
                    })
                    ->requiresConfirmation(),

                Tables\Actions\Action::make('reject')
                    ->label('رفض')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => auth()->user()?->isAdmin() && in_array($record->current_status, ['pending', 'approved']))
                    ->form([
                        Forms\Components\Textarea::make('status_note')
                            ->label('سبب الرفض')
                            ->required()
                            ->maxLength(1000),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'current_status' => 'rejected',
                            'status_note' => $data['status_note']
                        ]);
                    })
                    ->requiresConfirmation(),

                Tables\Actions\Action::make('complete')
                    ->label('وضع كمكتمل')
                    ->icon('heroicon-o-check-badge')
                    ->color('gray')
                    ->visible(fn ($record) => auth()->user()?->isAdmin() && $record->current_status === 'approved')
                    ->action(function ($record) {
                        $record->update(['current_status' => 'completed']);
                    })
                    ->requiresConfirmation(),
            ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make()
            //             ->label('حذف المحدد')
            //             ->requiresConfirmation()
            //             ->visible(fn () => auth()->user()?->isAdmin()),
            //     ]),
            // ])
            ;
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
            'index' => Pages\ListDonations::route('/'),
            'create' => Pages\CreateDonation::route('/create'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
        ];
    }
}


