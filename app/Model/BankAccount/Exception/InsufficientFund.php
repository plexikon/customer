<?php
declare(strict_types=1);

namespace App\Model\BankAccount\Exception;

use App\Model\BankAccount\BankAccountId;

final class InsufficientFund extends BankAccountException
{
    public static function withBankAccountId(BankAccountId $bankAccountId, float $amount, float $currentBalance): self
    {
        $message = "Insufficient fund from bank account {$bankAccountId->toString()}, ";
        $message .= "Expected $amount, available $currentBalance";

        return new self($message);
    }
}
