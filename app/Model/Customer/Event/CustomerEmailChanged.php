<?php
declare(strict_types=1);

namespace App\Model\Customer\Event;

use App\Model\Customer\CustomerId;
use App\Model\Customer\EmailAddress;
use Plexikon\Chronicle\Aggregate\AggregateChanged;

final class CustomerEmailChanged extends AggregateChanged
{
    private ?EmailAddress $newEmail;
    private ?EmailAddress $oldEmail;

    public static function withData(CustomerId $customerId, EmailAddress $newEmail, EmailAddress $oldEmail): self
    {
        $self = self::occur($customerId->toString(), [
            'customer_new_email' => $newEmail->getValue(),
            'customer_old_name' => $oldEmail->getValue(),
        ]);

        $self->newEmail = $newEmail;
        $self->oldEmail = $oldEmail;

        return $self;
    }

    public function customerId(): CustomerId
    {
        return CustomerId::fromString($this->aggregateRootId());
    }

    public function newEmail(): EmailAddress
    {
        return $this->email ?? EmailAddress::fromString($this->payload['customer_new_email']);
    }

    public function oldEmail(): EmailAddress
    {
        return $this->email ?? EmailAddress::fromString($this->payload['customer_old_email']);
    }
}
