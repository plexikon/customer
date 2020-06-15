<?php
declare(strict_types=1);

namespace App\Model\BankAccount\Handler;

use App\Model\BankAccount\Command\DepositMoney;
use App\Model\BankAccount\Exception\BankAccountNotFound;
use App\Model\BankAccount\Repository\BankAccountList;

final class DepositMoneyHandler
{
    private BankAccountList $bankAccountList;

    public function __construct(BankAccountList $bankAccountList)
    {
        $this->bankAccountList = $bankAccountList;
    }

    public function command(DepositMoney $command): void
    {
        $bankAccountId = $command->bankAccountId();

        if(!$bankAccount = $this->bankAccountList->get($bankAccountId)){
            throw BankAccountNotFound::withId($bankAccountId);
        }

        $bankAccount->credit($command->transaction());

        $this->bankAccountList->store($bankAccount);
    }
}
