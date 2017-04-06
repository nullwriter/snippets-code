<?php

namespace Galpa\ProductsCustomer;

use Illuminate\Support\ServiceProvider;


class ProductsCustomerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/Views','pcustomer');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'pcustomer');
        $this->publishes([
            __DIR__.'/assets/js' => public_path('js/products_customer'),
        ], 'productscustomer');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
    }
}
