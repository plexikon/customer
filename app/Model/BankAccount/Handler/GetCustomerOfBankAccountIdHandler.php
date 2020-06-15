<?php
declare(strict_types=1);

namespace App\Model\BankAccount\Handler;

use App\Model\BankAccount\Query\GetCustomerOfBankAccountId;
use App\Model\BankAccount\Query\GetPaginatedBankAccounts;
use App\Projection\BankAccount\BankAccountFinder;
use React\Promise\Deferred;

final class GetCustomerOfBankAccountIdHandler
{
    private BankAccountFinder $bankAccountFinder;

    public function __construct(BankAccountFinder $bankAccountFinder)
    {
        $this->bankAccountFinder = $bankAccountFinder;
    }

    public function query(GetCustomerOfBankAccountId $query, Deferred $promise): void
    {
        $customer = $this->bankAccountFinder->customerOfBankAccountId(
            $query->bankAccountId()->toString()
        );

        $promise->resolve($customer);
    }
}
