<?php

namespace Database\Seeders;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Offer::factory()
            ->count(5)
            ->published()
            ->has(
                Product::factory()
                    ->count(10)
                    ->state(['state' => 'available']), 
                'products'
            )
            ->create();

        Offer::factory()
            ->count(3)
            ->draft()
            ->has(Product::factory()->count(2), 'products')
            ->create();

        Offer::factory()
            ->published()
            ->has(
                Product::factory()
                    ->count(5)
                    ->outOfStock(),
                'products'
            )
            ->create(['name' => 'Offre Spéciale Rupture']);
    }
}