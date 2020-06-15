<?php
declare(strict_types=1);

namespace App\Model\BankAccount\Exception;

use App\Model\BankAccount\BankAccountId;

final class BankAccountNotFound extends BankAccountException
{
    public static function withId(BankAccountId $bankAccountId): self
    {
        return new self("Bank account with id {$bankAccountId->toString()} not found");
    }
}
