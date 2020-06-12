<?php
declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Model\Customer\CustomerId;
use App\Model\Customer\EmailAddress;
use App\Model\Customer\Service\UniqueEmail;
use App\Projection\Customer\CustomerFinder;

final class UniqueEmailFromRead implements UniqueEmail
{
    private CustomerFinder $customerFinder;

    public function __construct(CustomerFinder $customerFinder)
    {
        $this->customerFinder = $customerFinder;
    }

    public function __invoke(EmailAddress $email): ?CustomerId
    {
        $customer = $this->customerFinder->customerOfEmail($email->getValue());

        return $customer ? $customer->getId() : null;
    }
}
