<?php
declare(strict_types=1);

namespace App\Model\Customer\Query;

use Plexikon\Reporter\Query;

final class GetPaginatedCustomers
{
    private int $limit;
    private string $orderBy;
    private ?string $columnName;

    public function __construct(int $limit = 100, string $orderBy = 'ASC', ?string $columnName = null)
    {
        $this->limit = $limit;
        $this->orderBy = $orderBy;
        $this->columnName = $columnName;
    }

    public function limit(): int
    {
        return $this->limit;
    }

    public function orderBy(): string
    {
        return $this->orderBy;
    }

    public function columnName(): ?string
    {
        return $this->columnName;
    }
}
