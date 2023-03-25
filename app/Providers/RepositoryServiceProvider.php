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
            'App\Interfaces\AccurateTokenInterfaces',
            'App\Repositories\AccurateTokenRepository',
        );
        $this->app->bind(
            'App\Interfaces\AccurateDatabaseInterfaces',
            'App\Repositories\AccurateDatabaseRepository',
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
