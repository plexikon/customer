<?php
declare(strict_types=1);

namespace App\Model\BankAccount\Handler;

use App\Model\BankAccount\Query\GetPaginatedBankAccounts;
use App\Projection\BankAccount\BankAccountFinder;
use React\Promise\Deferred;

final class GetPaginatedBankAccountsHandler
{
    private BankAccountFinder $bankAccountFinder;

    public function __construct(BankAccountFinder $bankAccountFinder)
    {
        $this->bankAccountFinder = $bankAccountFinder;
    }

    public function query(GetPaginatedBankAccounts $query, Deferred $promise): void
    {
        $customers = $this->bankAccountFinder->paginate(
            $query->limit(), $query->orderBy(), $query->orderColumn()
        );

        $promise->resolve($customers);
    }
}
