<?php
declare(strict_types=1);

namespace App\Model\Customer\Event;

use App\Model\Customer\CustomerId;
use App\Model\Customer\EmailAddress;
use Plexikon\Chronicle\Aggregate\AggregateChanged;

final class CustomerRegistered extends AggregateChanged
{
    private ?EmailAddress $email;
    private ?string $name;

    public static function withData(CustomerId $customerId, EmailAddress $email, string $name): self
    {
        $self = self::occur($customerId->toString(), [
            'customer_email' => $email->getValue(),
            'customer_name' => $name,
        ]);

        $self->email = $email;
        $self->name = $name;;

        return $self;
    }

    public function customerId(): CustomerId
    {
        return CustomerId::fromString($this->aggregateRootId());
    }

    public function email(): EmailAddress
    {
        return $this->email ?? EmailAddress::fromString($this->payload['customer_email']);
    }

    public function name(): string
    {
        return $this->name ?? $this->payload['customer_name'];
    }
}
