<?php

use Dinhdjj\AutoDBTransaction\AutoDBTransactionMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

it('not auto active db transaction on GET method', function (): void {
    $request = new Request();
    expect(DB::transactionLevel())->toBe(0);
    $result = (new AutoDBTransactionMiddleware())->handle($request, fn () => true && expect(DB::transactionLevel())->toBe(0));
    expect($result)->toBeTrue();
    expect(DB::transactionLevel())->toBe(0);
});

it('auto active db transaction', function ($method): void {
    $request = new Request();
    $request->setMethod($method);
    expect(DB::transactionLevel())->toBe(0);
    $result = (new AutoDBTransactionMiddleware())->handle($request, fn () => true && expect(DB::transactionLevel())->toBe(1));
    expect($result)->toBeTrue();
    expect(DB::transactionLevel())->toBe(0);
})->with(['POST', 'PUT', 'PATCH', 'delete', 'other']);
