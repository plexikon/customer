<?php

namespace App\Model\BankAccount\Repository;

use App\Model\BankAccount\BankAccount;
use App\Model\BankAccount\BankAccountId;

interface BankAccountList
{
    /**
     * @param BankAccountId $bankAccountId
     * @return BankAccount|null
     */
    public function get(BankAccountId $bankAccountId): ?BankAccount;

    /**
     * @param BankAccount $bankAccount
     */
    public function store(BankAccount $bankAccount): void;
}
