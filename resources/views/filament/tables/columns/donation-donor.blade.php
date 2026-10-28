@php
    $record = $getRecord();
    $url = $getUrl();
@endphp

<div class="flex items-center gap-2 px-3 py-4">
    @if ($url)
        <a href="{{ $url }}" wire:click.stop class="flex items-center gap-1 text-sm text-primary-600 hover:underline dark:text-primary-400">
            <x-filament::icon icon="heroicon-m-arrow-top-right-on-square" class="h-4 w-4" />
            <span>{{ $getState() ?: 'ضيف' }}</span>
        </a>
    @else
        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $getState() ?: 'ضيف' }}</span>
    @endif

    @if ($record->contributors_count > 0)
        <x-filament::icon-button
            icon="heroicon-o-user-group"
            label="عرض المساهمين الإضافيين"
            tooltip="عرض المساهمين الإضافيين"
            wire:click.stop="mountTableAction('view_contributors', '{{ $record->getKey() }}')"
        />
    @endif
</div>
