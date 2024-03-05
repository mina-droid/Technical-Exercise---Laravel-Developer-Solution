<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Variant;
use App\Models\Product;
use Exception;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            throw new Exception('No products found. Please seed the products table first.');
        }

        foreach ($products as $product) {
            Variant::factory()
                ->count(3)
                ->create(['product_id' => $product->id]);
        }
    }
}
