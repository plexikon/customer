<?php
declare(strict_types=1);

namespace App\Model\BankAccount\Handler;

use App\Model\BankAccount\Command\CreateFirstBankAccount;
use App\Model\BankAccount\Repository\BankAccountList;
use App\Model\Customer\Exception\CustomerNotFound;
use App\Model\Customer\Repository\CustomerCollection;

final class CreateFirstBankAccountHandler
{
    private BankAccountList $bankAccountList;
    private CustomerCollection $customerCollection;

    public function __construct(BankAccountList $bankAccountList, CustomerCollection $customerCollection)
    {
        $this->bankAccountList = $bankAccountList;
        $this->customerCollection = $customerCollection;
    }

    public function command(CreateFirstBankAccount $command): void
    {
        $customerId = $command->customerId();

        if (!$customer = $this->customerCollection->get($customerId)) {
            throw CustomerNotFound::withId($customerId);
        }

        $bankAccount = $customer->createFirstBankAccount($customerId, $command->bankAccountId());

        $this->bankAccountList->store($bankAccount);
    }
}
