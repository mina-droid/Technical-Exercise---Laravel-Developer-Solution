<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Option;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::factory()->count(3)->create()->each(function ($product) {
            // Create options for each product
            $options = Option::factory(100)->create();

            // Add variations to each product, associating them with options
            $product->variants()->createMany([
                [
                    'title' => 'Variant 1',
                    'option1' => $options[0]->values[0],
                    'option2' => $options[1]->values[0],
                    'price' => 19.99,
                    'stock' => rand(0, 100),
                ],
                [
                    'title' => 'Variant 2',
                    'option1' => $options[1]->values[0],
                    'option2' => $options[0]->values[0],
                    'price' => 29.99,
                    'stock' => rand(0, 100),
                ],
                // Add more variations as needed
            ]);
        });
    }
}
