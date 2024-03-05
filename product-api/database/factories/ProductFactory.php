<?php

namespace Database\Factories;

use App\Models\Option;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Variant;


class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->unique()->word,
            'is_in_stock' => $this->faker->boolean,
            'average_rating' => $this->faker->randomFloat(1, 1, 5),
            'default_variant_id' => null, // set default_variant to null initially
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Configure the model factory for the relationship.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            // Create 3 variants for each product
            $variants = Variant::factory()->count(3)->create(['product_id' => $product->id]);

            // Set the variant with the lowest price as the default variant
            $defaultVariant = $variants->sortBy('price')->first();
            $product->default_variant_id = $defaultVariant->id;
            $product->save();

            // Retrieve all options from the database
            $options = Option::all();

            // Attach options to the product
            foreach ($options as $option) {
                $product->options()->attach($option->id);
            }
        });
    }

    public function withVariant($attributes = [])
    {
        return $this->afterCreating(function (Product $product) use ($attributes) {
            Variant::factory()->create(array_merge(['product_id' => $product->id], $attributes));
        });
    }
}
