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
                'name' => 'Clothing',
                'description' => 'Shirts, pants, dresses, and other clothing items',
                'is_active' => true,
            ],
            [
                'name' => 'Food',
                'description' => 'Canned goods, fresh produce, and packaged food items',
                'is_active' => true,
            ],
            [
                'name' => 'Furniture',
                'description' => 'Chairs, tables, beds, and other furniture items',
                'is_active' => true,
            ],
            [
                'name' => 'Electronics',
                'description' => 'Phones, computers, appliances, and electronic devices',
                'is_active' => true,
            ],
            [
                'name' => 'Books',
                'description' => 'Textbooks, novels, educational materials',
                'is_active' => true,
            ],
            [
                'name' => 'Toys',
                'description' => 'Children\'s toys, games, and entertainment items',
                'is_active' => true,
            ],
            [
                'name' => 'Medical Supplies',
                'description' => 'First aid kits, medications, medical equipment',
                'is_active' => true,
            ],
            [
                'name' => 'Household Items',
                'description' => 'Kitchenware, bedding, cleaning supplies',
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
