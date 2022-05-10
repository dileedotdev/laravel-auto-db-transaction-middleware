<?php

namespace Dinhdjj\LaravelAutoDbTransactionMiddleware\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Dinhdjj\LaravelAutoDbTransactionMiddleware\LaravelAutoDbTransactionMiddlewareServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Dinhdjj\\LaravelAutoDbTransactionMiddleware\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-auto-db-transaction-middleware_table.php.stub';
        $migration->up();
        */
    }
}
