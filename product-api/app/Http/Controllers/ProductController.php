<?php

namespace App\Http\Controllers;

use App\Events\ProductOutOfStock;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Option;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Get all products
        $products = Product::query();

        // Apply filters
        if ($request->has('filter')) {
            $filters = $request->input('filter');

            // Filter by average_rating
            if (isset($filters['average_rating'])) {
                $products->where('average_rating', '>=', $filters['average_rating']);
            }

            // Filter by options
            if (isset($filters['options'])) {
                $values = explode(',', $filters['options']);

                // Get all options that have any of the provided values
                $optionIds = Option::where(function ($query) use ($values) {
                    foreach ($values as $value) {
                        $query->orWhereJsonContains('values', $value);
                    }
                })->pluck('id');

                // Filter the products based on these options
                $products->whereHas('variants.options', function ($query) use ($optionIds) {
                    $query->whereIn('option_id', $optionIds);
                });
            }

            // Filter by max_price
            if (isset($filters['max_price'])) {
                $products->whereHas('defaultVariant', function ($query) use ($filters) {
                    $query->where('price', '<=', (float)$filters['max_price']);
                });
            }
        }

        // Retrieve the products with the required relationships
        $products = $products->with(['variants.options', 'defaultVariant'])->get();

        // Format the products
        $formattedProducts = $this->formatProducts($products);

        return response()->json($formattedProducts);
    }

    private function formatProducts($products)
    {
        $formattedProducts = [];

        foreach ($products as $product) {
            // Retrieve 'Size' and 'Color' options for the product
            $sizeOptions = $product->variants->flatMap->options->where('name', 'Size')->pluck('values')->first();
            $colorOptions = $product->variants->flatMap->options->where('name', 'Color')->pluck('values')->first();

            // Check if 'Size' and 'Color' options are present
            if (!$sizeOptions || !$colorOptions) {
                continue; // Skip this product if options are missing
            }

            // Randomly select a size
            $selectedSize = $sizeOptions[array_rand($sizeOptions)];

            // Randomly select a color
            $selectedColor = $colorOptions[array_rand($colorOptions)];

            // format the product
            $formattedProduct = [
                'id' => $product->id,
                'title' => $product->title,
                'is_in_stock' => $product->is_in_stock,
                'average_rating' => $product->average_rating,
                'options' => [
                    'Size' => $sizeOptions,
                    'Color' => $colorOptions,
                ],
                'default_variant' => $product->defaultVariant ? [
                    'id' => $product->defaultVariant->id,
                    'title' => $product->defaultVariant->title,
                    'option1' => $selectedSize,
                    'option2' => $selectedColor,
                    'price' => $product->defaultVariant->price,
                    'stock' => $product->defaultVariant->stock,
                    'is_in_stock' => $product->defaultVariant->is_in_stock,
                ] : null,
            ];

            $formattedProducts[] = $formattedProduct;
        }

        return $formattedProducts;
    }

    public function updateStockStatus(Product $product, $newStock)
    {
        // stock status logic...
        $product->defaultVariant->stock = $newStock;
        $product->defaultVariant->is_in_stock = $newStock > 0 ? true : false;
        $product->defaultVariant->save();

        if ($newStock === 0 && $product->defaultVariant->is_in_stock) {
            // The product is now out of stock
            event(new ProductOutOfStock($product));
        }
    }
    public function getFirstOptionValues()
    {
        // Retrieve the first option
        $firstOption = Option::first();

        // Check if the option is not null
        if ($firstOption) {
            // Decode the 'values' JSON-encoded string into a PHP array
            $valuesArray = json_decode($firstOption->values, true);

            // $valuesArray contains the array of values
            return response()->json(['values' => $valuesArray]);
        } else {
            // Handle the case where the option is not found
            return response()->json(['error' => 'Option not found'], 404);
        }
    }
}
