<?php

namespace App\Providers;

use App\Interfaces\AccountBankTypeInterfaces;
use App\Interfaces\Accurate\AccurateCustomerInterfaces;
use App\Interfaces\Accurate\AccurateDatabaseInterfaces;
use App\Interfaces\Accurate\AccurateEmployeeInterfaces;
use App\Interfaces\Accurate\AccurateItemInterfaces;
use App\Interfaces\Accurate\AccurateSessionInterfaces;
use App\Interfaces\Accurate\AccurateTokenInterfaces;
use App\Interfaces\BankInterfaces;
use App\Interfaces\CategoryBankInterfaces;
use App\Repositories\AccountBankTypeRepository;
use App\Repositories\Accurate\AccurateCustomerRepository;
use App\Repositories\Accurate\AccurateDatabaseRepository;
use App\Repositories\Accurate\AccurateEmployeeRepository;
use App\Repositories\Accurate\AccurateItemRepository;
use App\Repositories\Accurate\AccurateSessionRepository;
use App\Repositories\Accurate\AccurateTokenRepository;
use App\Repositories\BankRepository;
use App\Repositories\CategoryBankRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            AccurateTokenInterfaces::class,
            AccurateTokenRepository::class,
        );
        $this->app->bind(
            AccurateDatabaseInterfaces::class,
            AccurateDatabaseRepository::class,
        );
        $this->app->bind(
            AccurateSessionInterfaces::class,
            AccurateSessionRepository::class,
        );
        $this->app->bind(
            AccurateCustomerInterfaces::class,
            AccurateCustomerRepository::class,
        );
        $this->app->bind(
            AccurateEmployeeInterfaces::class,
            AccurateEmployeeRepository::class
        );
        $this->app->bind(
            AccurateItemInterfaces::class,
            AccurateItemRepository::class
        );
        $this->app->bind(
            AccountBankTypeInterfaces::class,
            AccountBankTypeRepository::class
        );
        $this->app->bind(
            CategoryBankInterfaces::class,
            CategoryBankRepository::class
        );
        $this->app->bind(
            BankInterfaces::class,
            BankRepository::class
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
