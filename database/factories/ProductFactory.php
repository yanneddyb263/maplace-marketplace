<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'seller_id' => Seller::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'category' => fake()->randomElement(['Mode', 'Maison', 'Électronique', 'Beauté', 'Artisanat']),
            'price' => fake()->numberBetween(1000, 250000),
            'image_url' => null,
            'is_active' => true,
        ];
    }
}
