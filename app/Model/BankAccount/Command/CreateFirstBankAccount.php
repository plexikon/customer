<?php
declare(strict_types=1);

namespace App\Model\BankAccount\Command;

use App\Model\BankAccount\BankAccountId;
use App\Model\Customer\CustomerId;
use Plexikon\Reporter\Command;

final class CreateFirstBankAccount extends Command
{
    public static function forCustomer(string $customerId, string $bankAccountId): self
    {
        return new self([
            'customer_id' => $customerId,
            'bank_account_id' => $bankAccountId
        ]);
    }

    public function customerId(): CustomerId
    {
        return CustomerId::fromString($this->payload['customer_id']);
    }

    public function bankAccountId(): BankAccountId
    {
        return BankAccountId::fromString($this->payload['bank_account_id']);
    }
}
