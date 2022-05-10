<?php

use Dinhdjj\AutoDBTransaction\AutoDBTransactionMiddleware;
use Dinhdjj\AutoDBTransaction\Tests\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

$createUser = function (): void {
    User::create();
};

$createUserWithException = function (): void {
    User::create();
    throw new \Exception();
};

Route::get('create-user', $createUser)->middleware(AutoDBTransactionMiddleware::class);
Route::post('create-user', $createUser)->middleware(AutoDBTransactionMiddleware::class);
Route::put('create-user', $createUser)->middleware(AutoDBTransactionMiddleware::class);
Route::patch('create-user', $createUser)->middleware(AutoDBTransactionMiddleware::class);
Route::delete('create-user', $createUser)->middleware(AutoDBTransactionMiddleware::class);

Route::get('create-user-exception', $createUserWithException)->middleware(AutoDBTransactionMiddleware::class);
Route::post('create-user-exception', $createUserWithException)->middleware(AutoDBTransactionMiddleware::class);
Route::put('create-user-exception', $createUserWithException)->middleware(AutoDBTransactionMiddleware::class);
Route::patch('create-user-exception', $createUserWithException)->middleware(AutoDBTransactionMiddleware::class);
Route::delete('create-user-exception', $createUserWithException)->middleware(AutoDBTransactionMiddleware::class);

Route::post('redundant-commit-transaction', function (): void {
    User::create();
    DB::commit();
})->middleware(AutoDBTransactionMiddleware::class);

Route::post('miss-commit-transaction', function (): void {
    DB::beginTransaction();
    User::create();
})->middleware(AutoDBTransactionMiddleware::class);

Route::get('unexpected-auto-commit', function (): void {
    DB::beginTransaction();
    User::create();
})->middleware(AutoDBTransactionMiddleware::class);
