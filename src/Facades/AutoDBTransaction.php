<?php

namespace Dinhdjj\AutoDBTransaction\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void beginTransaction()    Begin a transaction
 * @method static void commit()              Commit it's transaction
 * @method static void rollBack()            Rollback it's transaction
 * @method static void reset()               Reset state of instance
 * @method static ?int getTransactionLevel() get internal $transactionLevel
 *
 * @see \Dinhdjj\AutoDBTransaction\AutoDBTransaction
 */
class AutoDBTransaction extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'auto-db-transaction';
    }
}
