<?php

namespace Dinhdjj\AutoDBTransaction\Tests;

use Dinhdjj\AutoDBTransaction\AutoDBTransactionServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

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
            AutoDBTransactionServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');

        include __DIR__.'/web.php';

        $migration = include __DIR__.'/create_users_table.php';
        $migration->up();
    }
}
