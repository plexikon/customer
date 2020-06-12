<?php
declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Model\Customer\Customer;
use App\Model\Customer\CustomerId;
use App\Model\Customer\Repository\CustomerCollection;
use Plexikon\Chronicle\Support\Contracts\Aggregate\AggregateRepository;

final class ChronicleCustomerRepository implements CustomerCollection
{
    private $aggregateRepository;

    public function __construct(AggregateRepository $aggregateRepository)
    {
        $this->aggregateRepository = $aggregateRepository;
    }

    public function get(CustomerId $customerId): ?Customer
    {
        /** @var Customer $customer */
        $customer = $this->aggregateRepository->retrieve($customerId);

        return $customer->exists() ? $customer : null;
    }

    public function store(Customer $customer): void
    {
        $this->aggregateRepository->persist($customer);
    }

    public function getAggregateRepository(): AggregateRepository
    {
        return $this->aggregateRepository;
    }
}
