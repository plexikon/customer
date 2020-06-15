<?php
declare(strict_types=1);

namespace App\Model\BankAccount\Event;

use App\Model\BankAccount\BankAccountId;
use App\Model\BankAccount\Transaction;
use Plexikon\Chronicle\Aggregate\AggregateChanged;

final class MoneyDeposit extends AggregateChanged
{
    private Transaction $transaction;

    public static function toBankAccount(BankAccountId $bankAccountId,
                                         Transaction $transaction,
                                         float $newBalance,
                                         float $oldBalance): self
    {
        $self = self::occur($bankAccountId->toString(), [
            'transaction_method' => $transaction->getOperation()->getValue(),
            'amount' => $transaction->getAmount()->getValue(),
            'new_balance' => $newBalance,
            'old_balance' => $oldBalance
        ]);

        $self->transaction = $transaction;

        return $self;
    }

    public function bankAccountId(): BankAccountId
    {
        return BankAccountId::fromString($this->aggregateRootId());
    }

    public function transaction(): Transaction
    {
        return $this->transaction ??
            Transaction::credit($this->payload['transaction_method'], $this->payload['amount']);
    }

    public function newBalance(): float
    {
        return $this->payload['new_balance'];
    }

    public function oldBalance(): float
    {
        return $this->payload['old_balance'];
    }
}
