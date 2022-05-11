<?php

namespace Dinhdjj\AutoDBTransaction;

use Dinhdjj\AutoDBTransaction\AutoDBTransaction as Main;
use Dinhdjj\AutoDBTransaction\Facades\AutoDBTransaction;
use Illuminate\Log\Events\MessageLogged;
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

    /**
     * @infection-ignore-all
     */
    public function packageBooted(): void
    {
        if (class_exists(MessageLogged::class)) {
            // starting from L5.4 MessageLogged event class was introduced
            // https://github.com/laravel/framework/commit/57c82d095c356a0fe0f9381536afec768cdcc072
            $this->app['events']->listen(MessageLogged::class, function (MessageLogged $log): void {
                $this->handleLog($log->level, $log->message, $log->context);
            });
        } else {
            $this->app['events']->listen('illuminate.log', function ($level, $message, $context): void {
                $this->handleLog($level, $message, $context);
            });
        }
    }

    /**
     * Attach the event to the current transaction.
     *
     * @param string $level
     * @param mixed  $message
     * @param mixed  $context
     *
     * @return mixed
     * @infection-ignore-all
     */
    protected function handleLog($level, $message, $context)
    {
        if (
            isset($context['exception'])
            && $context['exception'] instanceof Throwable
        ) {
            AutoDBTransaction::rollBack();

            return;
        }

        if ($message instanceof Throwable) {
            AutoDBTransaction::rollBack();

            return;
        }
    }
}
