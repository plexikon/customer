<?php
declare(strict_types=1);

namespace App\Model\Customer\Handler;

use App\Model\Customer\Command\ChangeCustomerEmail;
use App\Model\Customer\Exception\CustomerAlreadyExists;
use App\Model\Customer\Exception\CustomerNotFound;
use App\Model\Customer\Repository\CustomerCollection;
use App\Model\Customer\Service\UniqueEmail;

final class ChangeCustomerEmailHandler
{
    private CustomerCollection $customerCollection;
    private UniqueEmail $uniqueEmail;

    public function __construct(CustomerCollection $customerCollection, UniqueEmail $uniqueEmail)
    {
        $this->customerCollection = $customerCollection;
        $this->uniqueEmail = $uniqueEmail;
    }

    public function command(ChangeCustomerEmail $command): void
    {
        $customerId = $command->customerId();

        if (!$customer = $this->customerCollection->get($customerId)) {
            throw CustomerNotFound::withId($customerId);
        }

        $newEmail = $command->email();

        if ($newEmail->sameValueAs($customer->getEmail())) {
            return;
        }

        if ($id = ($this->uniqueEmail)($newEmail)) {
            throw CustomerAlreadyExists::withEmail($newEmail);
        }

        $customer->changeEmail($newEmail);

        $this->customerCollection->store($customer);
    }
}
