<?php
declare(strict_types=1);

namespace App\Model\Customer;

use Plexikon\Chronicle\Support\Aggregate\HasAggregateId;
use Plexikon\Chronicle\Support\Contracts\Aggregate\AggregateId;

final class CustomerId implements AggregateId
{
    use HasAggregateId;
}
