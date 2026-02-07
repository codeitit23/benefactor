<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itemStatuses = [
            [
                'name' => 'New',
                'description' => 'Brand new, unused items',
                'color' => 'green',
                'is_active' => true,
            ],
            [
                'name' => 'Good',
                'description' => 'Slightly used but in good condition',
                'color' => 'blue',
                'is_active' => true,
            ],
            [
                'name' => 'Fair',
                'description' => 'Used items that need some repair',
                'color' => 'yellow',
                'is_active' => true,
            ],
            [
                'name' => 'Poor',
                'description' => 'Heavily used items',
                'color' => 'orange',
                'is_active' => true,
            ],
            [
                'name' => 'Damaged',
                'description' => 'Items that are damaged and need repair',
                'color' => 'red',
                'is_active' => true,
            ],
        ];

        foreach ($itemStatuses as $itemStatus) {
            \App\Models\ItemStatus::firstOrCreate(
                ['name' => $itemStatus['name']],
                $itemStatus
            );
        }
    }
}
