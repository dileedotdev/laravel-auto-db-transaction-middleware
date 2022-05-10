<?php

use Dinhdjj\AutoDBTransaction\AutoDBTransaction;
use Dinhdjj\AutoDBTransaction\Exceptions\HandledTransactionException;
use Dinhdjj\AutoDBTransaction\Exceptions\OtherUnhandledDBTransactionsException;
use Illuminate\Support\Facades\DB;

test('committing case is working', function (): void {
    $instance = new AutoDBTransaction();

    expect(DB::transactionLevel())->toBe(0);
    $instance->beginTransaction();
    expect(DB::transactionLevel())->toBe(1);

    $instance->commit();
    expect(DB::transactionLevel())->toBe(0);
    expect($instance->getTransactionLevel())->toBeNull();
});

test('roll backing case is working', function (): void {
    $instance = new AutoDBTransaction();

    expect(DB::transactionLevel())->toBe(0);
    $instance->beginTransaction();
    expect(DB::transactionLevel())->toBe(1);

    $instance->rollBack();
    expect(DB::transactionLevel())->toBe(0);
    expect($instance->getTransactionLevel())->toBeNull();
});

it('throws OtherUnhandledDBTransactionsException if exists other unhandled DB transactions', function (): void {
    $instance = new AutoDBTransaction();
    $instance->beginTransaction();
    DB::beginTransaction();
    // expected a rollBack or commit
    $instance->rollBack();
})->throws(OtherUnhandledDBTransactionsException::class, 'Expected transaction level is 1, but got 2.');

it('throws HandledTransactionException if exists other unhandled DB transactions', function (): void {
    $instance = new AutoDBTransaction();
    $instance->beginTransaction();
    DB::rollBack();
    // expected a rollBack or commit
    $instance->commit();
})->throws(HandledTransactionException::class, 'Expected transaction level is 1, but got 0.');
