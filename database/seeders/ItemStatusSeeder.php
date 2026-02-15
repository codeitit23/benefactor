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
                'is_active' => true,
            ],
            [
                'name' => 'Good',
                'is_active' => true,
            ],
            [
                'name' => 'Fair',
                'is_active' => true,
            ],
            [
                'name' => 'Poor',
                'is_active' => true,
            ],
            [
                'name' => 'Damaged',
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