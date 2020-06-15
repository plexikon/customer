<?php
declare(strict_types=1);

namespace App\ProcessManager;

use App\Model\BankAccount\BankAccountId;
use App\Model\BankAccount\Command\CreateFirstBankAccount;
use App\Model\Customer\Event\CustomerRegistered;
use Plexikon\Reporter\Support\Publisher\LazyPublisher;

final class CreateFirstBankAccountOnCustomerRegistration
{
    private LazyPublisher $publisher;

    public function __construct(LazyPublisher $publisher)
    {
        $this->publisher = $publisher;
    }

    public function onEvent(CustomerRegistered $event): void
    {
        $this->publisher->publishCommand(
            CreateFirstBankAccount::forCustomer(
                $event->customerId()->toString(), BankAccountId::create()->toString()
            )
        );
    }
}
