<?php

namespace Database\Factories;

use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Option;
use App\Models\Product;
use Exception;

class VariantFactory extends Factory
{
    protected $model = Variant::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'option1' => Option::factory(),
            'option2' => Option::factory(),
            'title' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'stock' => $this->faker->numberBetween(0, 100),
            'is_in_stock' => $this->faker->boolean,
        ];
    }

    /**
     * Indicate that the variant belongs to specific options.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function configure()
    {
        return $this->afterCreating(function (Variant $variant) {
            // Retrieve all options from the database
            $options = Option::all();

            // If there are not enough options, create additional options
            while ($options->count() < 2) {
                Option::factory()->create();
                $options = Option::all();
            }

            // Attach the first option to the variant
            $variant->options()->attach($options[0]->id);

            // Set option1 field
            $variant->option1 = $options[0]->name;

            // Attach the second option to the variant
            $variant->options()->attach($options[1]->id);

            // Set option2 field
            $variant->option2 = $options[1]->name;

            $variant->save();
        });
    }
}
