<?php
declare(strict_types=1);

namespace App\Model\BankAccount\Command;

use App\Model\BankAccount\Amount;
use App\Model\BankAccount\BankAccountId;
use App\Model\BankAccount\CreditOperation;
use App\Model\BankAccount\Transaction;
use Plexikon\Reporter\Command;

final class DepositMoney extends Command
{
    public static function toBankAccount(string $bankAccountId, string $operationType, float $amount): self
    {
        return new self([
            'bank_account_id' => $bankAccountId,
            'credit_operation' => $operationType,
            'amount' => $amount
        ]);
    }

    public function bankAccountId(): BankAccountId
    {
        return BankAccountId::fromString($this->payload['bank_account_id']);
    }

    public function transaction(): Transaction
    {
        return Transaction::credit($this->payload['credit_operation'], $this->payload['amount']);
    }
}
