<?php
declare(strict_types=1);

namespace App\Projection\BankAccount;

use App\Model\BankAccount\Event\MoneyDeposit;
use App\Model\BankAccount\Event\MoneyWithdrawn;
use App\Projection\Tables;
use Illuminate\Database\Schema\Blueprint;
use Plexikon\Chronicle\Aggregate\AggregateChanged;
use Plexikon\Chronicle\Support\ReadModel\ConnectionReadModel;
use Plexikon\Chronicle\Support\ReadModel\HasConnectionOperation;

final class BankAccountReadModel extends ConnectionReadModel
{
    use HasConnectionOperation;

    /**
     * @param AggregateChanged|MoneyDeposit|MoneyWithdrawn $event
     */
    protected function incrementAmount(AggregateChanged $event): void
    {
        $this->queryBuilder()
            ->where('id', $event->aggregateRootId())
            ->increment('balance', $event->transaction()->getAmount()->getValue());
    }

    protected function up(): callable
    {
        return function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('customer_id');
            $table->decimal('balance')->default(0);
        };
    }

    protected function tableName(): string
    {
        return Tables::BANK_ACCOUNT;
    }
}
