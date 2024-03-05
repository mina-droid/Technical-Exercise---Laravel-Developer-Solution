<?php

namespace Database\Factories;

use App\Models\Option;
use Illuminate\Database\Eloquent\Factories\Factory;

class OptionFactory extends Factory
{
    protected $model = Option::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['Size', 'Color']),
            'values' => $this->faker->randomElement([['Small', 'Medium', 'Large'], ['White', 'Black', 'Red', 'Blue']]),
        ];
    }
}
