<?php
declare(strict_types=1);

namespace App\Model\BankAccount\Query;

use App\Model\BankAccount\BankAccountId;

final class GetCustomerOfBankAccountId
{
    private string $bankAccountId;

    public function __construct(string $bankAccountId)
    {
        $this->bankAccountId = $bankAccountId;
    }

    public function bankAccountId(): BankAccountId
    {
        return BankAccountId::fromString($this->bankAccountId);
    }
}
