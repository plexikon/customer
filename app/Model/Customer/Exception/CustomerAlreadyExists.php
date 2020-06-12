<?php
declare(strict_types=1);

namespace App\Model\Customer\Exception;

use App\Model\Customer\CustomerId;
use App\Model\Customer\EmailAddress;

final class CustomerAlreadyExists extends CustomerException
{
    public static function withId(CustomerId $customerId): self
    {
        return new self("Customer with id {$customerId->toString()} already exists");
    }

    public static function withEmail(EmailAddress $email): self
    {
        return new self("Customer with email {$email->getValue()} already exists");
    }
}
