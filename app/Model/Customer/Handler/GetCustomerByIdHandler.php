<?php
declare(strict_types=1);

namespace App\Model\Customer\Handler;

use App\Model\Customer\Query\GetCustomerById;
use App\Projection\Customer\CustomerFinder;
use React\Promise\Deferred;

final class GetCustomerByIdHandler
{
    private CustomerFinder $customerFinder;

    public function __construct(CustomerFinder $customerFinder)
    {
        $this->customerFinder = $customerFinder;
    }

    public function query(GetCustomerById $query, Deferred $promise): void
    {
        $customer = $this->customerFinder->customerOfId($query->customerId()->toString());

        $promise->resolve($customer);
    }
}
