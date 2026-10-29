<?php

namespace App\Filament\Forms\Components;

use App\Models\Donation;
use App\Models\User;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class DonationContributors extends Repeater
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->view('filament.forms.components.donation-contributors')
            ->label('المساهمون الإضافيون')
            ->hiddenLabel()
            ->helperText(fn (DonationContributors $component): string => $component->getRecord()?->exists
                ? 'تُحفظ تغييرات المساهمين مباشرة.'
                : 'سيتم حفظ المساهمين عند إنشاء التبرع.')
            ->relationship('contributors')
            ->defaultItems(0)
            ->reorderable(false)
            ->schema(static::contributionFields())
            ->addAction(fn (Action $action): Action => $action
                ->label('إضافة مساهم')
                ->icon('heroicon-m-plus')
                ->color('primary')
                ->authorize(fn (): bool => (bool) auth()->user()?->isAdmin())
                ->modalHeading('إضافة مساهم')
                ->modalWidth('lg')
                ->modalSubmitActionLabel('إضافة')
                ->form(static::contributionFields())
                ->action(function (array $data, DonationContributors $component): void {
                    if ($donation = $component->savedDonation()) {
                        $donation->contributors()->create($data);
                        $component->reloadContributors();

                        return;
                    }

                    $items = $component->getState() ?? [];
                    if ($key = $component->generateUuid()) {
                        $items[$key] = $data;
                    } else {
                        $items[] = $data;
                    }
                    $component->state($items);
                    $component->callAfterStateUpdated();
                }))
            ->extraItemActions([
                Action::make('edit')
                    ->label('تعديل المساهمة')
                    ->icon('heroicon-m-pencil-square')
                    ->authorize(fn (): bool => (bool) auth()->user()?->isAdmin())
                    ->modalHeading('تعديل المساهمة')
                    ->modalWidth('lg')
                    ->modalSubmitActionLabel('حفظ')
                    ->form(static::contributionFields())
                    ->fillForm(fn (array $arguments, DonationContributors $component): array =>
                        $component->getState()[$arguments['item']] ?? [])
                    ->action(function (array $arguments, array $data, DonationContributors $component): void {
                        if ($donation = $component->savedDonation()) {
                            $donation->contributors()->findOrFail($component->contributionId($arguments))->update($data);
                            $component->reloadContributors();

                            return;
                        }

                        $items = $component->getState() ?? [];

                        if (! array_key_exists($arguments['item'], $items)) {
                            return;
                        }

                        $items[$arguments['item']] = $data;
                        $component->state($items);
                        $component->callAfterStateUpdated();
                    }),
            ])
            ->deleteAction(fn (Action $action): Action => $action
                ->label('حذف المساهمة')
                ->authorize(fn (): bool => (bool) auth()->user()?->isAdmin())
                ->action(function (array $arguments, DonationContributors $component): void {
                    if ($donation = $component->savedDonation()) {
                        $donation->contributors()->findOrFail($component->contributionId($arguments))->delete();
                        $component->reloadContributors();

                        return;
                    }

                    $items = $component->getState() ?? [];
                    unset($items[$arguments['item']]);
                    $component->state($items);
                    $component->callAfterStateUpdated();
                }));
    }

    private function savedDonation(): ?Donation
    {
        abort_unless(auth()->user()?->isAdmin() && ! $this->isHidden(), 403);

        $record = $this->getRecord();

        return $record instanceof Donation && $record->exists ? $record : null;
    }

    private function contributionId(array $arguments): string
    {
        $key = (string) ($arguments['item'] ?? '');
        abort_unless(str_starts_with($key, 'record-'), 404);

        return substr($key, strlen('record-'));
    }

    private function reloadContributors(): void
    {
        $this->getRecord()->unsetRelation('contributors');
        $this->loadStateFromRelationships(andHydrate: true);
        $this->callAfterStateUpdated();
    }

    private static function contributionFields(): array
    {
        return [
            Select::make('user_id')
                ->label('المساهم')
                ->options(fn () => User::query()->orderBy('name')->limit(50)->pluck('name', 'id'))
                ->searchable()
                ->getSearchResultsUsing(fn (string $search) => User::query()
                    ->where(fn ($query) => $query->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%"))
                    ->orderBy('name')
                    ->limit(50)
                    ->pluck('name', 'id'))
                ->getOptionLabelUsing(fn ($value) => User::find($value)?->name)
                ->exists('users', 'id')
                ->required(),

            TextInput::make('amount')
                ->label('المبلغ')
                ->prefix('USD')
                ->numeric()
                ->minValue(0.01)
                ->maxValue(99999999.99)
                ->step(0.01)
                ->rule('decimal:0,2')
                ->required(),

            Textarea::make('notes')
                ->label('ملاحظات')
                ->maxLength(1000)
                ->rows(2),
        ];
    }

    public function getContributorNames(): array
    {
        return User::query()
            ->whereKey(array_column($this->getState() ?? [], 'user_id'))
            ->pluck('name', 'id')
            ->all();
    }
}
