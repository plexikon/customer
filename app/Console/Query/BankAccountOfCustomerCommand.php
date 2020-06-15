<?php
declare(strict_types=1);

namespace App\Console\Query;

use App\Model\BankAccount\BankAccountId;
use App\Model\BankAccount\Event\MoneyDeposit;
use App\Model\BankAccount\Event\MoneyWithdrawn;
use App\Model\BankAccount\Query\GetCustomerOfBankAccountId;
use App\Projection\BankAccount\BankAccountModel;
use App\Projection\Customer\CustomerModel;
use App\Projection\Streams;
use Illuminate\Console\Command;
use Plexikon\Chronicle\Aggregate\AggregateChanged;
use Plexikon\Chronicle\Support\Contracts\Projector\ProjectorManager;
use Plexikon\Reporter\Support\Publisher\LazyPublisher;
use Ramsey\Uuid\Exception\InvalidUuidStringException;

final class BankAccountOfCustomerCommand extends Command
{
    protected $signature = 'app:query-customer_bank_account {bank_account_id}';

    private ProjectorManager $projectorManager;
    private LazyPublisher $publisher;

    public function __construct(ProjectorManager $projectorManager, LazyPublisher $publisher)
    {
        $this->projectorManager = $projectorManager;
        $this->publisher = $publisher;
        parent::__construct();
    }

    public function handle(): void
    {
        if (!$bankAccountId = $this->determineBankAccountId()) {
            $this->error("invalid bank account id");
            return;
        }

        if (!$customer = $this->customerOfBankAccountId($bankAccountId)) {
            $this->error("Customer with id {$bankAccountId->toString()} not found");
            return;
        }

        $query = $this->projectorManager->createQuery();
        $query
            ->withQueryFilter($this->projectorManager->projectionQueryFilter())
            ->initialize(fn(): array => [
                'debit' => 0,
                'credit' => 0,
                'balance' => 0,
                'events_balance' => 0,
            ])
            ->fromStreams(Streams::BANK_ACCOUNT)
            ->whenAny($this->queryHandlers($bankAccountId))
            ->run(false);

        $this->table(
            ['customer', 'debit op', 'credit op', 'balance', 'events balance'],
            [array_merge([$customer->getEmail()->getValue()], $query->getState())]
        );
    }

    private function queryHandlers(BankAccountId $bankAccountId): callable
    {
        return function (array $state, AggregateChanged $event) use ($bankAccountId): array {
            if ($bankAccountId->toString() === $event->aggregateRootId()) {
                if ($event instanceof MoneyDeposit) {
                    $state['credit']++;
                    $state['balance'] = $event->newBalance();
                    $state['events_balance'] += $event->transaction()->getAmount()->getValue();
                }

                if ($event instanceof MoneyWithdrawn) {
                    $state['debit']++;
                    $state['balance'] = $event->newBalance();
                    $state['events_balance'] += $event->transaction()->getAmount()->getValue();
                }
            }

            return $state;
        };
    }

    private function customerOfBankAccountId(BankAccountId $bankAccountId): ?CustomerModel
    {
        $bankAccount = $this->publisher->publishQuery(
            new GetCustomerOfBankAccountId($bankAccountId->toString())
        );

        if (!$bankAccount instanceof BankAccountModel) {
            return null;
        }

        return $bankAccount->getRelation('customer');
    }

    private function determineBankAccountId(): ?BankAccountId
    {
        try {
            return BankAccountId::fromString($this->argument('bank_account_id'));
        } catch (InvalidUuidStringException $exception) {
            return null;
        }
    }
}
