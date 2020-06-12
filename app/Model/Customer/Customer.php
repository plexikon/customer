<?php
declare(strict_types=1);

namespace App\Model\Customer;

use App\Model\Customer\Event\CustomerEmailChanged;
use App\Model\Customer\Event\CustomerRegistered;
use Plexikon\Chronicle\Support\Aggregate\HasAggregateRoot;
use Plexikon\Chronicle\Support\Contracts\Aggregate\AggregateId;
use Plexikon\Chronicle\Support\Contracts\Aggregate\AggregateRoot;

final class Customer implements AggregateRoot
{
    use HasAggregateRoot;

    private EmailAddress $email;
    private string $name;

    public static function register(CustomerId $customerId, EmailAddress $email, string $name): self
    {
        $self = new self($customerId);
        $self->recordThat(CustomerRegistered::withData($customerId, $email, $name));

        return $self;
    }

    public function changeEmail(EmailAddress $email): void
    {
        if ($email->sameValueAs($this->email)) {
            return;
        }

        $this->recordThat(CustomerEmailChanged::withData($this->customerId(), $email, $this->email));
    }

    /**
     * @return CustomerId|AggregateId
     */
    public function customerId(): CustomerId
    {
        return $this->aggregateId;
    }

    public function getEmail(): EmailAddress
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    protected function applyCustomerRegistered(CustomerRegistered $event): void
    {
        $this->email = $event->email();
        $this->name = $event->name();
    }

    protected function applyCustomerEmailChanged(CustomerEmailChanged $event): void
    {
        $this->email = $event->newEmail();
    }
}
