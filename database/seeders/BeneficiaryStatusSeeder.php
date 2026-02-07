<?php

namespace Database\Seeders;

use App\Models\BeneficiaryStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BeneficiaryStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Active'],
            ['name' => 'Inactive'],
            ['name' => 'Pending'],
        ];

        foreach ($statuses as $status) {
            BeneficiaryStatus::create($status);
        }
    }
}
