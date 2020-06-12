<?php
declare(strict_types=1);

namespace App\Projection\Customer;

use App\Model\Customer\CustomerId;
use App\Model\Customer\EmailAddress;
use App\Projection\Tables;
use Illuminate\Database\Eloquent\Model;

final class CustomerModel extends Model
{
    protected $table = Tables::CUSTOMER;
    protected $guarded = ['*'];
    protected $keyType = 'string';
    public $incrementing = false;

    public function getId(): CustomerId
    {
        return CustomerId::fromString($this->getKey());
    }

    public function getEmail(): EmailAddress
    {
        return EmailAddress::fromString($this['email']);
    }

    public function getCustomerName(): string
    {
        return $this['name'];
    }
}
