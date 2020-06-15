<?php
declare(strict_types=1);

namespace App\Console\ReadModel;

use App\Console\AppPersistentProjectionCommand;
use App\Model\BankAccount\Event\FirstBankAccountCreated;
use App\Model\BankAccount\Event\MoneyDeposit;
use App\Model\BankAccount\Event\MoneyWithdrawn;
use App\Projection\BankAccount\BankAccountReadModel;
use App\Projection\Streams;

final class BankAccountPersistentProjectionCommand extends AppPersistentProjectionCommand
{
    protected $signature = 'app:read_model-bank_account';

    public function handle(): void
    {
        $projection = $this->withProjection(Streams::BANK_ACCOUNT, BankAccountReadModel::class);

        $projection
            ->initialize(fn(): array => ['balance' => 0.0])
            ->fromStreams(Streams::BANK_ACCOUNT)
            ->when($this->bankAccountHandlers())
            ->run(true);
    }

    private function bankAccountHandlers(): array
    {
        return [
            'first-bank-account-created' => function (array $state, FirstBankAccountCreated $event): void {
                $this->readModel()->stack('insert', [
                    'id' => $event->aggregateRootId(),
                    'customer_id' => $event->customerId()->toString(),
                ]);
            },

            'money-deposit' => function (array $state, MoneyDeposit $event): array {
                $this->readModel()->stack('incrementAmount', $event);

                $state['balance'] += $event->transaction()->getAmount()->getValue();

                return $state;
            },

            'money-withdrawn' => function (array $state, MoneyWithdrawn $event): array {
                $this->readModel()->stack('incrementAmount', $event);

                $state['balance'] += $event->transaction()->getAmount()->getValue();

                return $state;
            }
        ];
    }
}
