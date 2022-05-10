<?php

namespace Dinhdjj\AutoDBTransaction;

use Dinhdjj\AutoDBTransaction\AutoDBTransaction as Main;
use Dinhdjj\AutoDBTransaction\Facades\AutoDBTransaction;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Throwable;

class AutoDBTransactionServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('auto-db-transaction')
        ;
    }

    public function packageRegistered(): void
    {
        $this->app->singleton('auto-db-transaction', fn () => new Main());
    }

    public function packageBooted(): void
    {
        resolve(ExceptionHandler::class)->reportable(function (Throwable $e): void {
            AutoDBTransaction::rollBack();
        });
    }
}
