<?php
declare(strict_types=1);

namespace App\Model\BankAccount\Event;

use App\Model\BankAccount\BankAccountId;
use App\Model\Customer\CustomerId;
use Plexikon\Chronicle\Aggregate\AggregateChanged;

final class FirstBankAccountCreated extends AggregateChanged
{
    private ?CustomerId $customerId;

    public static function forCustomer(CustomerId $customerId, BankAccountId $bankAccountId): self
    {
        $self = self::occur($bankAccountId->toString(), [
            'customer_id' => $customerId->toString(),
        ]);

        $self->customerId = $customerId;

        return $self;
    }

    public function customerId(): CustomerId
    {
        return $this->customerId ?? CustomerId::fromString($this->payload['customer_id']);
    }

    public function bankAccountId(): BankAccountId
    {
        return BankAccountId::fromString($this->aggregateRootId());
    }
}
