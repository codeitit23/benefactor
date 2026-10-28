<div class="space-y-4">
    @foreach ($contributors as $contributor)
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
            <div class="flex items-center justify-between gap-4">
                <span class="font-medium text-gray-950 dark:text-white">{{ $contributor->user->name }}</span>
                <span class="text-sm font-medium text-gray-950 dark:text-white" dir="ltr">USD {{ number_format((float) $contributor->amount, 2) }}</span>
            </div>

            @if (filled($contributor->notes))
                <p class="mt-2 whitespace-pre-line text-sm text-gray-600 dark:text-gray-400">{{ $contributor->notes }}</p>
            @endif
        </div>
    @endforeach
</div>
