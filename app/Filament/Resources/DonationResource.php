<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Filament\Resources\DonationResource\RelationManagers;
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

    protected static ?string $navigationLabel = 'Donations';

    protected static ?string $modelLabel = 'Donation';

    protected static ?string $pluralModelLabel = 'Donations';

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
        return $user->isAdmin() || $record->user_id === $user->id;
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
                Forms\Components\Section::make('Donation Information')
                    ->schema([
                        Forms\Components\TextInput::make('donation_number')
                            ->label('Donation Number')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Auto-generated'),

                        Forms\Components\Hidden::make('user_id')
                            ->default(fn () => auth()->id())
                            ->required(),

                        Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\Select::make('donor_id')
                                    ->label('Donor')
                                    ->options(\App\Models\User::where('active', true)->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required(fn () => auth()->user()?->isAdmin())
                                    ->visible(fn () => auth()->user()?->isAdmin())
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if (auth()->user()?->isAdmin()) {
                                            $set('user_id', $state);
                                        }
                                    })
                                    ->default(fn () => auth()->user()?->isAdmin() ? null : auth()->id()),

                                Forms\Components\Select::make('donation_type')
                                    ->options([
                                        'item' => 'Item Donation',
                                        'cash' => 'Cash Donation',
                                    ])
                                    ->default('item')
                                    ->live()
                                    ->required(),


                                Forms\Components\Select::make('item_type_id')
                                    ->label('Type of Item')
                                    ->options(\App\Models\ItemType::active()->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->visible(fn (Forms\Get $get) => $get('donation_type') === 'item'),

                                Forms\Components\Select::make('item_subcategory_id')
                                    ->label('Item Subcategory')
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
                                    ->label('Status of Item')
                                    ->options(\App\Models\ItemStatus::active()->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->visible(fn (Forms\Get $get) => $get('donation_type') === 'item'),

                                // Cash donation fields
                                Forms\Components\Select::make('payment_method')
                                    ->options([
                                        'cash' => 'Cash',
                                        'wish' => 'Wish',
                                        'omt' => 'OMT',
                                        'credit_card' => 'Credit Card',
                                    ])
                                    ->required()
                                    ->visible(fn (Forms\Get $get) => $get('donation_type') === 'cash'),

                                Forms\Components\TextInput::make('amount')
                                    ->label('Amount')
                                    ->numeric()
                                    ->prefix('USD')
                                    ->minValue(0)
                                    ->required()
                                    ->visible(fn (Forms\Get $get) => $get('donation_type') === 'cash'),
                            ]),
                    ]),

                Forms\Components\Section::make('Item Details')
                    ->schema([
                        Forms\Components\FileUpload::make('item_images')
                            ->label('Item Pictures (Max 5)')
                            ->multiple()
                            ->maxFiles(5)
                            ->image()
                            ->imageEditor()
                            ->directory('donations/items')
                            ->visibility('public')
                            ->visible(fn (Forms\Get $get) => $get('donation_type') === 'item'),

                        Forms\Components\FileUpload::make('item_video')
                            ->label('Item Video')
                            ->acceptedFileTypes(['video/mp4', 'video/avi', 'video/mov', 'video/wmv'])
                            ->maxSize(51200) // 50MB
                            ->directory('donations/videos')
                            ->visibility('public')
                            ->visible(fn (Forms\Get $get) => $get('donation_type') === 'item'),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('pickup_date')
                                    ->label('Date of Pickup')
                                    ->minDate(now())
                                    ->visible(fn (Forms\Get $get) => $get('donation_type') === 'item'),

                                Forms\Components\Textarea::make('notes')
                                    ->label('Notes')
                                    ->rows(4)
                                    ->maxLength(1000),
                            ]),
                    ])
                    ->visible(fn (Forms\Get $get) => $get('donation_type') === 'item'),

                Forms\Components\Section::make('Admin Actions')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('beneficiary_id')
                                    ->label('Relate to Beneficiary')
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
                                        'pending' => 'Pending',
                                        'approved' => 'Approved',
                                        'rejected' => 'Rejected',
                                        'completed' => 'Completed',
                                    ])
                                    ->default('pending')
                                    ->required(),

                                Forms\Components\Textarea::make('status_note')
                                    ->label('Status Note')
                                    ->rows(3)
                                    ->maxLength(1000)
                                    ->helperText('Add a note explaining the status change, especially for rejections'),
                            ]),

                        Forms\Components\FileUpload::make('beneficiary_images')
                            ->label('Beneficiary Pictures')
                            ->multiple()
                            ->maxFiles(10)
                            ->image()
                            ->imageEditor()
                            ->directory('donations/beneficiaries')
                            ->visibility('public'),

                        Forms\Components\FileUpload::make('beneficiary_video')
                            ->label('Beneficiary Video')
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
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Donor')
                    ->searchable()
                    ->sortable()
                    ->visible(fn () => auth()->user()?->isAdmin()),

                Tables\Columns\TextColumn::make('beneficiary.name')
                    ->label('Donated To')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Not assigned'),

                Tables\Columns\TextColumn::make('donation_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'success',
                        'item' => 'info',
                    }),

                Tables\Columns\TextColumn::make('itemType.name')
                    ->label('Item Type')
                    ->visible(fn () => auth()->user()?->isAdmin()),

                Tables\Columns\TextColumn::make('itemStatus.name')
                    ->label('Item Status')
                    ->badge()
                    ->color(fn ($record) => $record->itemStatus?->color ?? 'gray')
                    ->visible(fn () => auth()->user()?->isAdmin()),

                Tables\Columns\TextColumn::make('amount')
                    ->money('USD')
                    ->visible(fn () => auth()->user()?->isAdmin()),

                Tables\Columns\TextColumn::make('current_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'completed' => 'gray',
                    }),

                Tables\Columns\TextColumn::make('pickup_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('beneficiary_id')
                    ->label('Beneficiary')
                    ->relationship('beneficiary', 'name')
                    ->preload()
                    ->searchable(),

                Tables\Filters\SelectFilter::make('donation_type')
                    ->options([
                        'item' => 'Item Donation',
                        'cash' => 'Cash Donation',
                    ]),

                Tables\Filters\SelectFilter::make('current_status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'completed' => 'Completed',
                    ]),

                Tables\Filters\SelectFilter::make('item_type_id')
                    ->label('Item Type')
                    ->options(\App\Models\ItemType::active()->pluck('name', 'id')),

                Tables\Filters\Filter::make('pickup_date')
                    ->form([
                        Forms\Components\DatePicker::make('pickup_from'),
                        Forms\Components\DatePicker::make('pickup_until'),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('relate_beneficiary')
                    ->label('Relate Beneficiary')
                    ->icon('heroicon-o-user-plus')
                    ->color('primary')
                    ->visible(fn () => auth()->user()?->isAdmin())
                    ->form([
                        Forms\Components\Select::make('beneficiary_id')
                            ->label('Select Beneficiary')
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
                            ->helperText('Search by name or phone number'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update(['beneficiary_id' => $data['beneficiary_id']]);
                    }),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => auth()->user()?->isAdmin() && $record->current_status === 'pending')
                    ->action(function ($record) {
                        $record->update(['current_status' => 'approved']);
                    })
                    ->requiresConfirmation(),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => auth()->user()?->isAdmin() && in_array($record->current_status, ['pending', 'approved']))
                    ->form([
                        Forms\Components\Textarea::make('status_note')
                            ->label('Rejection Reason')
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
                    ->label('Mark Complete')
                    ->icon('heroicon-o-check-badge')
                    ->color('gray')
                    ->visible(fn ($record) => auth()->user()?->isAdmin() && $record->current_status === 'approved')
                    ->action(function ($record) {
                        $record->update(['current_status' => 'completed']);
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->visible(fn () => auth()->user()?->isAdmin()),
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
            'index' => Pages\ListDonations::route('/'),
            'create' => Pages\CreateDonation::route('/create'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
        ];
    }
}
