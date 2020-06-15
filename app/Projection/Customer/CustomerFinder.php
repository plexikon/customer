<?php
declare(strict_types=1);

namespace App\Projection\Customer;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

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
        $customer = $this->model->find($customerId);

        return $customer;
    }

    public function customerOfEmail(string $email): ?CustomerModel
    {
        /** @var CustomerModel $customer */
        $customer = $this->newCustomerModel()->where('email', $email)->first();

        return $customer;
    }

    public function paginate(int $limit = 100, string $orderBy = 'ASC', ?string $columnName = null): LengthAwarePaginator
    {
        $model = $this->newCustomerModel();

        if ($columnName) {
            $model = $model->orderBy($columnName, $orderBy);
        }

        return $model->paginate($limit);
    }

    public function newCustomerModel(): Builder
    {
        return $this->model->newInstance()->newQuery();
    }
}
