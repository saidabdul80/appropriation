<?php

namespace App\Providers;

use App\Services\BudgetService;
use Illuminate\Support\ServiceProvider;

class BudgetServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BudgetService::class, function ($app) {
            return new BudgetService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
