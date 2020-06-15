<?php
declare(strict_types=1);

namespace App\Model\BankAccount;

use App\Model\Enum;

/**
 * @method static CreditOperation CASH()
 * @method static CreditOperation CHEQUE()
 * @method static CreditOperation BITCOIN()
 */
final class CreditOperation extends Enum implements TransactionMethod
{
    public const CASH = 'cash';
    public const CHEQUE = 'cheque';
    public const BITCOIN = 'bitcoin';
}
