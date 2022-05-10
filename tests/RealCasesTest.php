<?php

use Illuminate\Support\Facades\DB;
use function Pest\Laravel\get;

test('unexpected-auto-commit', function (): void {
    get('unexpected-auto-commit');
    expect(DB::transactionLevel())->toBe(1);
});
