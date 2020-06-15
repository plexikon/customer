<?php
declare(strict_types=1);

namespace App\Model\BankAccount;

use App\Model\Enum;

/**
 * @method static DebitOperation CASH()
 * @method static DebitOperation CHEQUE()
 * @method static DebitOperation BITCOIN()
 * @method static DebitOperation CREDIT_CARD()
 * @method static DebitOperation TRANSFER()
 */
final class DebitOperation extends Enum implements TransactionMethod
{
    public const CASH = 'cash';
    public const CHEQUE = 'cheque';
    public const BITCOIN = 'bitcoin';
    public const CREDIT_CARD = 'credit_card';
    public const TRANSFER = 'transfer';
}
