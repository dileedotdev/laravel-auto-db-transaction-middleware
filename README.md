# A laravel-middleware auto activate db-transaction on each request

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dinhdjj/laravel-auto-db-transaction-middleware.svg?style=flat-square)](https://packagist.org/packages/dinhdjj/laravel-auto-db-transaction-middleware)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/dinhdjj/laravel-auto-db-transaction-middleware/run-tests?label=tests)](https://github.com/dinhdjj/laravel-auto-db-transaction-middleware/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/dinhdjj/laravel-auto-db-transaction-middleware/Check%20&%20fix%20styling?label=code%20style)](https://github.com/dinhdjj/laravel-auto-db-transaction-middleware/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/dinhdjj/laravel-auto-db-transaction-middleware.svg?style=flat-square)](https://packagist.org/packages/dinhdjj/laravel-auto-db-transaction-middleware)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Requirements

    * Laravel 9+
    * php 8.1+

## Installation

You can install the package via composer:

```bash
composer require dinhdjj/laravel-auto-db-transaction-middleware
```

## Usage

Firstly you should register the middleware to group or you can use middleware in specific routes.

```php
/**
 * The application's route middleware groups.
 *
 * @var  array
 */
protected $middlewareGroups = [
    'web' => [
        ...,
        \Dinhdjj\AutoDBTransaction\AutoDBTransactionMiddleware::class,
    ],

    'api' => [
        ...,
        \Dinhdjj\AutoDBTransaction\AutoDBTransactionMiddleware::class,
    ]
```

Above is all thing you need to do.

## How it works

Below I will show you how it auto activate db-transaction on each request.

1. It only activate `beginTransaction` on method `POST`, `PUT`, `PATCH`, `DELETE`...(not `GET`) methods.
2. In all cases it will auto `commit` and only `rollback` when it encounter an unhandled exception.
3. It will also throw exceptions in some cases.
   - When you miss `commit` or `rollback` on your own `beginTransaction`.
   - When you use redundant `commit` or `rollback` db-transaction.

## Exception handler

When an exception is thrown, in most cases behaver of this package will `rollBack` the db-transaction, but if you not use `default logging exception handler of laravel` the package will evaluate that you handled the exception and it will continue `commit` db-transaction.

Below is the example of cases not use `default logging exception handler of laravel`

```php
//App\Exceptions\Handler

$this->reportable(function (InvalidOrderException $e) {
    //
})->stop();

$this->reportable(function (InvalidOrderException $e) {
    return false;
});
```

or

```php
namespace App\Exceptions;
 
use Exception;
 
class InvalidOrderException extends Exception
{
    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        return true;
        // or
        return null;
    }
}
```

If you not use `default logging exception handler of laravel` and you want to `rollBack` the db-transaction, you can use this:

```php
// 1. use your own db-transaction
DB::beginTransaction();
// your code
DB::rollBack();
```

```php
// 2. You helper method to rollback the package's db-transaction
$this->reportable(function (InvalidOrderException $e) {
    \Dinhdjj\AutoDBTransaction\Facades::rollBack();
})->stop();
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [dinhdjj](https://github.com/dinhdjj)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
