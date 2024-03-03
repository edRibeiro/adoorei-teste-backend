<?php

namespace App\Providers;

use App\Repositories\Contracts\SaleRepositoryInterface;
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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
