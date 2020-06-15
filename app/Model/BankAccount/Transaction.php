<?php
declare(strict_types=1);

namespace App\Model\BankAccount;

final class Transaction
{
    private TransactionMethod $operation;
    private Amount $amount;

    protected function __construct(TransactionMethod $operation, Amount $amount)
    {
        $this->operation = $operation;
        $this->amount = $amount;
    }

    public static function debit(string $operation, float $amount): self
    {
        $amount = abs($amount);

        return new self(DebitOperation::byValue($operation), Amount::fromNegative(-$amount));
    }

    public static function credit(string $operation, float $amount): self
    {
        $amount = abs($amount);

        return new self(CreditOperation::byValue($operation), Amount::fromPositive($amount));
    }

    /**
     * @return TransactionMethod|DebitOperation|CreditOperation
     */
    public function getOperation(): TransactionMethod
    {
        return $this->operation;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }
}
