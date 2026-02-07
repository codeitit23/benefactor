<?php

namespace Database\Seeders;

use App\Models\SeverityLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeverityLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            ['name' => 'Low'],
            ['name' => 'Medium'],
            ['name' => 'High'],
        ];

        foreach ($levels as $level) {
            SeverityLevel::create($level);
        }
    }
}
