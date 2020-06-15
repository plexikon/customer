<?php
declare(strict_types=1);

namespace App\Model\BankAccount\Command;

use App\Model\BankAccount\Amount;
use App\Model\BankAccount\BankAccountId;
use App\Model\BankAccount\CreditOperation;
use App\Model\BankAccount\DebitOperation;
use App\Model\BankAccount\Transaction;
use Plexikon\Reporter\Command;

final class WithdrawMoney extends Command
{
    public static function fromBankAccount(string $bankAccountId, string $debitOperation, float $amount): self
    {
        return new self([
            'bank_account_id' => $bankAccountId,
            'debit_operation' => $debitOperation,
            'amount' => $amount
        ]);
    }

    public function bankAccountId(): BankAccountId
    {
        return BankAccountId::fromString($this->payload['bank_account_id']);
    }

    public function transaction(): Transaction
    {
        return Transaction::debit($this->payload['debit_operation'], $this->payload['amount']);
    }
}
