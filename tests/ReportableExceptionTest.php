<?php

use Dinhdjj\AutoDBTransaction\Exceptions\HandledTransactionException;
use Dinhdjj\AutoDBTransaction\Exceptions\OtherUnhandledDBTransactionsException;
use Illuminate\Support\Facades\DB;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('create-user route in GET method', function (): void {
    get('create-user');

    expect(DB::transactionLevel())->toBe(0);

    assertDatabaseCount('users', 1);
});

test('create-user route', function ($method): void {
    $this->{$method}('create-user');

    expect(DB::transactionLevel())->toBe(0);

    assertDatabaseCount('users', 1);
})->with(['post', 'put', 'patch', 'delete']);

test('create-user-exception route in GET method', function (): void {
    get('create-user-exception');

    expect(DB::transactionLevel())->toBe(0);

    assertDatabaseCount('users', 1);
});

test('create-user-exception route', function ($method): void {
    $this->{$method}('create-user-exception');

    expect(DB::transactionLevel())->toBe(0);

    assertDatabaseCount('users', 0);
})->with(['post', 'put', 'patch', 'delete']);

test('redundant-commit-transaction route', function (): void {
    post('redundant-commit-transaction');
})->throws(HandledTransactionException::class);

test('miss-commit-transaction route', function (): void {
    post('miss-commit-transaction');
})->throws(OtherUnhandledDBTransactionsException::class);
