<?php
declare(strict_types=1);

namespace App\Model\Customer\Exception;

use App\Model\Customer\CustomerId;

final class CustomerNotFound extends CustomerException
{
    public static function withId(CustomerId $customerId): self
    {
        return new self("Customer with id {$customerId->toString()} not found");
    }
}
