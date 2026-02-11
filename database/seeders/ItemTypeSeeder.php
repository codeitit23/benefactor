<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itemTypes = [
            [
                'name' => 'أجهزة منزلية',
                'is_active' => true,
            ],
            [
                'name' => 'أغراض خارجية',
                'is_active' => true,
            ],
            [
                'name' => 'الكترونيات',
                'is_active' => true,
            ],
            [
                'name' => 'غرفة الجلوس',
                'is_active' => true,
            ],
            [
                'name' => 'غرفة الطعام',
                'is_active' => true,
            ],
            [
                'name' => 'غرفة النوم',
                'is_active' => true,
            ],
            [
                'name' => 'اغراض مطبخ',
                'is_active' => true,
            ],
            [
                'name' => 'اغراض حمام',
                'is_active' => true,
            ],
            [
                'name' => 'غرفة اطفال',
                'is_active' => true,
            ],
            [
                'name' => 'أغراض طبية',
                'is_active' => true,
            ],
        ];

        foreach ($itemTypes as $itemType) {
            \App\Models\ItemType::firstOrCreate(
                ['name' => $itemType['name']],
                $itemType
            );
        }
    }
}
