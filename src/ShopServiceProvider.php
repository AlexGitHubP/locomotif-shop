<?php

namespace Locomotif\Shop;

use Illuminate\Support\ServiceProvider;
use Locomotif\Shop\Commands\CheckFGOInvoiceStatus;
use Locomotif\Shop\Commands\CheckResellerInvoices;

class ShopServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Locomotif\Shop\Controller\OrdersController');

        if ($this->app->runningInConsole()) {
            $this->commands([
                CheckFGOInvoiceStatus::class,
                CheckResellerInvoices::class,
            ]);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/Routes/routes.php');
        $this->loadViewsFrom(__DIR__.'/views/orders', 'orders');
        $this->loadViewsFrom(__DIR__.'/views/notifications', 'notifications');
        
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        $this->publishes([
            __DIR__.'/views' => resource_path('views/locomotif/shop'),
        ]);
        
    }
}
