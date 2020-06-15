<?php
declare(strict_types=1);

namespace App\Projection\BankAccount;

use App\Model\BankAccount\Amount;
use App\Model\BankAccount\BankAccountId;
use App\Projection\Customer\CustomerModel;
use App\Projection\Tables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class BankAccountModel extends Model
{
    protected $table = Tables::BANK_ACCOUNT;
    protected $guarded = ['*'];
    protected $keyType = 'string';
    public $incrementing = false;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerModel::class, 'customer_id', 'id');
    }

    public function getId(): BankAccountId
    {
        return BankAccountId::fromString($this->getKey());
    }

    public function getAmount(): Amount
    {
        return $this['amount'] > 0
            ? Amount::fromPositive($this['amount'])
            : Amount::fromNegative($this['amount']);

    }
}
