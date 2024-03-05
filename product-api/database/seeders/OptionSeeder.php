<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Option;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure 'Size' option exists
        Option::updateOrCreate(
            ['name' => 'Size'],
            ['values' => ['Small', 'Medium', 'Large']]
        );

        // Ensure 'Color' option exists
        Option::updateOrCreate(
            ['name' => 'Color'],
            ['values' => ['White', 'Black', 'Red', 'Blue']]
        );
    }
}
