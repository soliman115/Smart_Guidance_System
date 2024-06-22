<?php

namespace App\Providers;

use App\Services\AdminStatisticsService;
use App\Services\UserStatisticsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserStatisticsService::class, function ($app) {
            return new UserStatisticsService();
        });

        $this->app->singleton(AdminStatisticsService::class, function ($app) {
            return new AdminStatisticsService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
