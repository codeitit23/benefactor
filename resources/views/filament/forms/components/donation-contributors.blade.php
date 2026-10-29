@php
    $items = $getState() ?? [];
    $names = $getContributorNames();
    $addAction = $getAction($getAddActionName());
    $editAction = $getExtraItemActions()['edit'];
    $deleteAction = $getAction($getDeleteActionName());
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <x-filament-tables::container data-donation-contributors>
        <div class="fi-ta-header flex flex-wrap items-center justify-between gap-3 p-4 sm:px-6">
            <h3 class="fi-ta-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">{{ $getLabel() }}</h3>
            @if ($addAction->isVisible())
                {{ $addAction }}
            @endif
        </div>

        <div class="fi-ta-content relative overflow-x-auto">
            <x-filament-tables::table :aria-label="$getLabel()">
                <x-slot name="header">
                    <x-filament-tables::header-cell scope="col">المساهم</x-filament-tables::header-cell>
                    <x-filament-tables::header-cell scope="col">المبلغ (USD)</x-filament-tables::header-cell>
                    <x-filament-tables::header-cell scope="col">ملاحظات</x-filament-tables::header-cell>
                    <x-filament-tables::header-cell scope="col" alignment="end">الإجراءات</x-filament-tables::header-cell>
                </x-slot>

                @forelse ($items as $key => $item)
                    <x-filament-tables::row wire:key="{{ $getStatePath() }}.contributor.{{ $key }}">
                        <x-filament-tables::cell>
                            <div class="px-3 py-4 text-sm leading-6 text-gray-950 dark:text-white">{{ $names[$item['user_id'] ?? ''] ?? 'غير محدد' }}</div>
                        </x-filament-tables::cell>
                        <x-filament-tables::cell>
                            <div class="px-3 py-4 text-sm leading-6 text-gray-950 dark:text-white">
                                <span dir="ltr">USD {{ number_format((float) ($item['amount'] ?? 0), 2) }}</span>
                            </div>
                        </x-filament-tables::cell>
                        <x-filament-tables::cell>
                            <div class="whitespace-pre-line break-words px-3 py-4 text-sm leading-6 text-gray-950 dark:text-white">{{ filled($item['notes'] ?? null) ? $item['notes'] : '—' }}</div>
                        </x-filament-tables::cell>
                        <x-filament-tables::cell>
                            <div class="flex items-center justify-end gap-3 px-3 py-4">
                                {{ $editAction(['item' => $key]) }}
                                {{ $deleteAction(['item' => $key]) }}
                            </div>
                        </x-filament-tables::cell>
                    </x-filament-tables::row>
                @empty
                    <x-filament-tables::row>
                        <x-filament-tables::cell colspan="4">
                            <x-filament-tables::empty-state
                                icon="heroicon-o-user-group"
                                heading="لا يوجد مساهمون إضافيون"
                                description="اضغط إضافة مساهم لإضافة مساهمة إلى هذا التبرع."
                            />
                        </x-filament-tables::cell>
                    </x-filament-tables::row>
                @endforelse
            </x-filament-tables::table>
        </div>
    </x-filament-tables::container>
</x-dynamic-component>
