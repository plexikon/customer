<?php
declare(strict_types=1);

namespace App\Model\Customer\Command;

use App\Model\Customer\CustomerId;
use App\Model\Customer\EmailAddress;
use Assert\Assertion;
use Plexikon\Reporter\Command;

final class RegisterCustomer extends Command
{
    public static function withData(string $customerId, string $email, string $name): self
    {
        return new self([
            'customer_id' => $customerId,
            'customer_email' => $email,
            'customer_name' => $name
        ]);
    }

    public function customerId(): CustomerId
    {
        return CustomerId::fromString($this->payload['customer_id']);
    }

    public function email(): EmailAddress
    {
        return EmailAddress::fromString($this->payload['customer_email']);
    }

    public function name(): string
    {
        Assertion::notBlank($this->payload['customer_name']);

        return $this->payload['customer_name'];
    }
}
