<?php
declare(strict_types=1);

namespace App\Model\Customer\Repository;

use App\Model\Customer\Customer;
use App\Model\Customer\CustomerId;

interface CustomerCollection
{
    /**
     * @param CustomerId $customerId
     * @return Customer|null
     */
    public function get(CustomerId $customerId): ?Customer;

    /**
     * @param Customer $customer
     */
    public function store(Customer $customer): void;
}
