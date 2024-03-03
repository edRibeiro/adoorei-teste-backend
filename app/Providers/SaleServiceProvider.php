<?php

namespace App\Providers;

use App\Repositories\Contracts\ProductSaleRepositoryInterface;
use App\Repositories\Contracts\SaleRepositoryInterface;
use App\Repositories\ProductSaleEloquentRepository;
use App\Repositories\SaleEloquentRepository;
use Illuminate\Support\ServiceProvider;

class SaleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            SaleRepositoryInterface::class,
            SaleEloquentRepository::class
        );

        $this->app->bind(
            ProductSaleRepositoryInterface::class,
            ProductSaleEloquentRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
