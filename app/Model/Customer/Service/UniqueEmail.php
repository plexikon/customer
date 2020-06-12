<?php

namespace App\Model\Customer\Service;

use App\Model\Customer\CustomerId;
use App\Model\Customer\EmailAddress;

interface UniqueEmail
{
    /**
     * @param EmailAddress $email
     * @return CustomerId|null
     */
    public function __invoke(EmailAddress $email): ?CustomerId;
}
