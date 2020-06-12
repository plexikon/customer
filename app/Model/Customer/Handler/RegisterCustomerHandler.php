<?php
declare(strict_types=1);

namespace App\Model\Customer\Handler;

use App\Model\Customer\Command\RegisterCustomer;
use App\Model\Customer\Customer;
use App\Model\Customer\Exception\CustomerAlreadyExists;
use App\Model\Customer\Repository\CustomerCollection;
use App\Model\Customer\Service\UniqueEmail;

final class RegisterCustomerHandler
{
    private CustomerCollection $customerCollection;
    private UniqueEmail $uniqueEmail;

    public function __construct(CustomerCollection $customerCollection, UniqueEmail $uniqueEmail)
    {
        $this->customerCollection = $customerCollection;
        $this->uniqueEmail = $uniqueEmail;
    }

    public function command(RegisterCustomer $command): void
    {
        $customerId = $command->customerId();

        if($customer = $this->customerCollection->get($customerId)){
            throw CustomerAlreadyExists::withId($customerId);
        }

        $email = $command->email();

        if($id = ($this->uniqueEmail)($email)){
            throw CustomerAlreadyExists::withEmail($email);
        }

        $customer = Customer::register($customerId, $email, $command->name());

        $this->customerCollection->store($customer);
    }
}
