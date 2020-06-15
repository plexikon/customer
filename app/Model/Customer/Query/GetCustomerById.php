<?php
declare(strict_types=1);

namespace App\Model\Customer\Query;

use App\Model\Customer\CustomerId;

final class GetCustomerById
{
    private string $customerId;

    public function __construct(string $customerId)
    {
        $this->customerId = $customerId;
    }

    public function customerId(): CustomerId
    {
        return CustomerId::fromString($this->customerId);
    }
}
