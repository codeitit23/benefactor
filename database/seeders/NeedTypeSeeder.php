<?php

namespace Database\Seeders;

use App\Models\NeedType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NeedTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Food'],
            ['name' => 'Clothing'],
            ['name' => 'Shelter'],
            ['name' => 'Medical'],
            ['name' => 'Education'],
        ];

        foreach ($types as $type) {
            NeedType::create($type);
        }
    }
}
