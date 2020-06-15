<?php
declare(strict_types=1);

namespace App\Model\Customer\Handler;

use App\Model\Customer\Query\GetPaginatedCustomers;
use App\Projection\Customer\CustomerFinder;
use React\Promise\Deferred;

final class GetPaginatedCustomerHandler
{
    private CustomerFinder $customerFinder;

    public function __construct(CustomerFinder $customerFinder)
    {
        $this->customerFinder = $customerFinder;
    }

    public function query(GetPaginatedCustomers $query, Deferred $promise): void
    {
        $customers = $this->customerFinder->paginate($query->limit(), $query->orderBy(), $query->columnName());

        $promise->resolve($customers);
    }
}
