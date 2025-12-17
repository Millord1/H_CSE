<?php

namespace Database\Factories;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer>
 */
class OfferFactory extends Factory
{
    protected $model = Offer::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->sentence(3);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'image'       => 'offers/fake-image.jpg',
            'state'       => $this->faker->randomElement(['draft', 'published', 'archived']),
            'created_at'  => now(),
            'updated_at'  => now(),
        ];
    }

    /**
     * Specific state for a published Offer
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'state' => 'published',
        ]);
    }

    /**
     * Specific state for a draft Offer
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'state' => 'draft',
        ]);
    }
}