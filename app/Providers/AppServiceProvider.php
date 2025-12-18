<?php

namespace App\Providers;

use App\Repositories\EloquentOfferRepository;
use App\Repositories\EloquentProductRepository;
use App\Repositories\Interfaces\OfferRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ProductRepositoryInterface::class,
            EloquentProductRepository::class
        );

        $this->app->bind(
            OfferRepositoryInterface::class,
            EloquentOfferRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
