<?php

namespace App\Providers;

use App\Interfaces\Accurate\AccurateCustomerInterfaces;
use App\Interfaces\Accurate\AccurateDatabaseInterfaces;
use App\Interfaces\Accurate\AccurateEmployeeInterfaces;
use App\Interfaces\Accurate\AccurateItemInterfaces;
use App\Interfaces\Accurate\AccurateSessionInterfaces;
use App\Interfaces\Accurate\AccurateTokenInterfaces;
use App\Interfaces\Auth\AuthInterfaces;
use App\Interfaces\Auth\PermissionInterfaces;
use App\Interfaces\Auth\RoleInterfaces;
use App\Interfaces\Bank\AccountBankTypeInterfaces;
use App\Interfaces\Bank\BankInterfaces;
use App\Interfaces\Bank\CategoryBankInterfaces;
use App\Interfaces\JournalVoucherUpload\JournalVoucherUploadInterfaces;
use App\Interfaces\Whitelist\WhitelistInterfaces;
use App\Repositories\Accurate\AccurateCustomerRepository;
use App\Repositories\Accurate\AccurateDatabaseRepository;
use App\Repositories\Accurate\AccurateEmployeeRepository;
use App\Repositories\Accurate\AccurateItemRepository;
use App\Repositories\Accurate\AccurateSessionRepository;
use App\Repositories\Accurate\AccurateTokenRepository;
use App\Repositories\Auth\AuthRepository;
use App\Repositories\Auth\PermissionRepository;
use App\Repositories\Auth\RoleRepository;
use App\Repositories\Bank\AccountBankTypeRepository;
use App\Repositories\Bank\BankRepository;
use App\Repositories\Bank\CategoryBankRepository;
use App\Repositories\JournalVoucherUpload\JournalVoucherUploadRepository;
use App\Repositories\Whitelist\WhitelistRepository;
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
        $this->app->bind(
            JournalVoucherUploadInterfaces::class,
            JournalVoucherUploadRepository::class
        );
        $this->app->bind(
            AuthInterfaces::class,
            AuthRepository::class
        );
        $this->app->bind(
            WhitelistInterfaces::class,
            WhitelistRepository::class
        );
        $this->app->bind(
            RoleInterfaces::class,
            RoleRepository::class
        );
        $this->app->bind(
            PermissionInterfaces::class,
            PermissionRepository::class
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
