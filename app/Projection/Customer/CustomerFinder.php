<?php
declare(strict_types=1);

namespace App\Projection\Customer;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final class CustomerFinder
{
    private CustomerModel $model;

    public function __construct(CustomerModel $model)
    {
        $this->model = $model;
    }

    public function customerOfId(string $customerId): ?CustomerModel
    {
        /** @var CustomerModel $customer */
        $customer = $this->newCustomerModel()->find($customerId);

        return $customer;
    }

    public function customerOfEmail(string $email): ?CustomerModel
    {
        /** @var CustomerModel $customer */
        $customer = $this->newCustomerModel()->where('email', $email)->first();

        return $customer;
    }

    public function newCustomerModel(): Builder
    {
        return $this->model->newInstance()->newQuery();
    }
}
