<?php

namespace Dinhdjj\AutoDBTransaction;

use Dinhdjj\AutoDBTransaction\Exceptions\HandledTransactionException;
use Dinhdjj\AutoDBTransaction\Exceptions\OtherUnhandledDBTransactionsException;
use Illuminate\Support\Facades\DB;

class AutoDBTransaction
{
    protected int|null $transactionLevel = null;

    /** get internal $transactionLevel */
    public function getTransactionLevel(): ?int
    {
        return $this->transactionLevel;
    }

    /** begin a transaction */
    public function beginTransaction(): void
    {
        DB::beginTransaction();
        $this->transactionLevel = DB::transactionLevel();
    }

    /** commit it's transaction */
    public function commit(): void
    {
        if ($this->shouldCloseTransaction()) {
            DB::commit();
            $this->reset();
        }
    }

    /** Rollback it's transaction */
    public function rollBack(): void
    {
        if ($this->shouldCloseTransaction()) {
            DB::rollBack();
            $this->reset();
        }
    }

    /** Check and throws whether should close transaction */
    protected function shouldCloseTransaction(): bool
    {
        if (null === $this->transactionLevel) {
            return false;
        }

        $message = 'Expected transaction level is '.$this->transactionLevel.', but got '.DB::transactionLevel().'.';

        if (DB::transactionLevel() > $this->transactionLevel) {
            throw new OtherUnhandledDBTransactionsException($message);
        }

        if (DB::transactionLevel() < $this->transactionLevel) {
            throw new HandledTransactionException($message);
        }

        return true;
    }

    /** Reset state of instance */
    public function reset(): void
    {
        $this->transactionLevel = null;
    }
}
