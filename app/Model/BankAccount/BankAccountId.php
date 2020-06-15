<?php
declare(strict_types=1);

namespace App\Model\BankAccount;

use Plexikon\Chronicle\Support\Aggregate\HasAggregateId;
use Plexikon\Chronicle\Support\Contracts\Aggregate\AggregateId;

final class BankAccountId implements AggregateId
{
    use HasAggregateId;
}
