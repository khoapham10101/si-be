<?php

namespace Modules\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'brand_id' => $this->faker->numberBetween(1, 10),
            'sku' => $this->faker->unique()->word . rand(1000, 9999),
            'description' => $this->faker->paragraph,
            'warranty_information' => $this->faker->sentence,
            'quantity' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'images' => json_encode([$this->faker->imageUrl()]),
        ];
    }
}
