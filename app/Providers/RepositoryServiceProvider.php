<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Interfaces\SupplierRepositoryInterface::class,
            \App\Repositories\Eloquent\SupplierRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\CltLayupRepositoryInterface::class,
            \App\Repositories\Eloquent\CltLayupRepository::class
        );
        
        $this->app->bind(
            \App\Repositories\Interfaces\CltLayerRepositoryInterface::class,
            \App\Repositories\Eloquent\CltLayerRepository::class
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
