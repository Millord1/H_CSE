<?php

namespace Database\Factories;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'offer_id'    => Offer::factory(), 
            'name'        => $this->faker->words(3, true),
            'sku'         => strtoupper($this->faker->unique()->bothify('PROD-####-????')),
            'image'       => 'products/fake-image.jpg',
            'price'       => $this->faker->randomFloat(2, 10, 1000),
            'state'       => $this->faker->randomElement(['available', 'out_of_stock', 'discontinued']),
            'created_at'  => now(),
            'updated_at'  => now(),
        ];
    }

    /**
     * Specific state for an 'Out of Stock' product
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'state' => 'out_of_stock',
        ]);
    }
}
