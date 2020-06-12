<?php
declare(strict_types=1);

namespace App\Model\Customer\Command;

use App\Model\Customer\CustomerId;
use App\Model\Customer\EmailAddress;
use Plexikon\Reporter\Command;

final class ChangeCustomerEmail extends Command
{
    public static function withData(string $customerId, string $newEmail): self
    {
        return new self([
            'customer_id' => $customerId,
            'customer_new_email' => $newEmail
        ]);
    }

    public function customerId(): CustomerId
    {
        return CustomerId::fromString($this->payload['customer_id']);
    }

    public function email(): EmailAddress
    {
        return EmailAddress::fromString($this->payload['customer_new_email']);
    }
}
