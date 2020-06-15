<?php
declare(strict_types=1);

namespace App\Model\BankAccount;

use App\Model\BankAccount\Event\FirstBankAccountCreated;
use App\Model\BankAccount\Event\MoneyDeposit;
use App\Model\BankAccount\Event\MoneyWithdrawn;
use App\Model\BankAccount\Exception\InsufficientFund;
use App\Model\Customer\CustomerId;
use Plexikon\Chronicle\Support\Aggregate\HasAggregateRoot;
use Plexikon\Chronicle\Support\Contracts\Aggregate\AggregateId;
use Plexikon\Chronicle\Support\Contracts\Aggregate\AggregateRoot;

final class BankAccount implements AggregateRoot
{
    use HasAggregateRoot;

    private CustomerId $customerId;
    private float $balance = 0.00;

    public static function create(BankAccountId $bankAccountId, CustomerId $customerId): self
    {
        $self = new self($bankAccountId);
        $self->recordThat(FirstBankAccountCreated::forCustomer($customerId, $bankAccountId));

        return $self;
    }

    public function credit(Transaction $transaction): void
    {
        $newBalance = $this->balance + $transaction->getAmount()->getValue();

        $this->recordThat(MoneyDeposit::toBankAccount(
            $this->bankAccountId(), $transaction, $newBalance, $this->balance)
        );
    }

    public function debit(Transaction $transaction): void
    {
        $amount = $transaction->getAmount()->getValue();

        if ($this->balance + $amount < 0) {
            throw InsufficientFund::withBankAccountId(
                $this->bankAccountId(), $amount, $this->balance()
            );
        }

        $this->recordThat(MoneyWithdrawn::fromBankAccount(
            $this->bankAccountId(), $transaction, $this->balance + $amount, $this->balance)
        );
    }

    protected function applyFirstBankAccountCreated(FirstBankAccountCreated $event): void
    {
        $this->customerId = $event->customerId();
    }

    protected function applyMoneyDeposit(MoneyDeposit $event): void
    {
        $this->balance = $event->newBalance();
    }

    protected function applyMoneyWithdrawn(MoneyWithdrawn $event): void
    {
        $this->balance = $event->newBalance();
    }

    /**
     * @return BankAccountId|AggregateId
     */
    public function bankAccountId(): BankAccountId
    {
        return $this->aggregateId();
    }

    public function customerId(): CustomerId
    {
        return $this->customerId;
    }

    public function balance(): float
    {
        return $this->balance;
    }
}
