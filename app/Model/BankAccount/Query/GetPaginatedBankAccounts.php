<?php
declare(strict_types=1);

namespace App\Model\BankAccount\Query;

final class GetPaginatedBankAccounts
{
    private int $limit;
    private string $orderBy;
    private ?string $orderColumn;

    public function __construct(int $limit = 100, string $orderBy = 'ASC', ?string $orderColumn = null)
    {
        $this->limit = $limit;
        $this->orderBy = $orderBy;
        $this->orderColumn = $orderColumn;
    }

    public function limit(): int
    {
        return $this->limit;
    }

    public function orderBy(): string
    {
        return $this->orderBy;
    }

    public function orderColumn(): ?string
    {
        return $this->orderColumn;
    }
}
