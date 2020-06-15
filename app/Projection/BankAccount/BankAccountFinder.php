<?php
declare(strict_types=1);

namespace App\Projection\BankAccount;

use App\Projection\Customer\CustomerModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

final class BankAccountFinder
{
    private BankAccountModel $model;

    public function __construct(BankAccountModel $model)
    {
        $this->model = $model;
    }

    public function paginate(int $limit = 100, string $orderBy = 'ASC', ?string $columnName = null): LengthAwarePaginator
    {
        $model = $this->newBankAccountModel();

        if ($columnName) {
            $model = $model->orderBy($columnName, $orderBy);
        }

        return $model->paginate($limit);
    }

    public function customerOfBankAccountId(string $bankAccountId): ?BankAccountModel
    {
        /** @var BankAccountModel $model */
        $model = $this->newBankAccountModel()->with('customer')->find($bankAccountId);

        return $model;
    }

    public function newBankAccountModel(): Builder
    {
        return $this->model->newInstance()->newQuery();
    }
}
