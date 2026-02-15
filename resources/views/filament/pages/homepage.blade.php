<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h1 class="text-2xl font-bold">Homepage</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Welcome to the homepage!
            </p>
        </div>
    </div>
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold">Recent Donations</h2>
            <ul class="mt-4 space-y-2">
                @foreach ($donations as $donation)
                    <li class="flex items-center justify-between">
                        <div>
                            <p class="font-medium">{{ $donation->donor_name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $donation->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="text-green-500 font-semibold">${{ number_format($donation->amount, 2) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>  
</x-filament-panels::page>
