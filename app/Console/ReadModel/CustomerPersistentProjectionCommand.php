<?php
declare(strict_types=1);

namespace App\Console\ReadModel;

use App\Console\AppPersistentProjectionCommand;
use App\Model\Customer\Event\CustomerEmailChanged;
use App\Model\Customer\Event\CustomerRegistered;
use App\Projection\Customer\CustomerReadModel;
use App\Projection\Streams;

final class CustomerPersistentProjectionCommand extends AppPersistentProjectionCommand
{
    protected $signature = 'app:read_model-customer';

    public function handle(): void
    {
        $projection = $this->withProjection(Streams::CUSTOMER, CustomerReadModel::class);

        $projection
            ->initialize(fn(): array => ['count_registered' => 0])
            ->fromStreams(Streams::CUSTOMER)
            ->when($this->customerHandlers())
            ->run(true);
    }

    private function customerHandlers(): array
    {
        return [
            'customer-registered' => function (array $state, CustomerRegistered $event): array {
                $this->readModel()->stack('insert', [
                    'id' => $event->aggregateRootId(),
                    'email' => $event->email()->getValue(),
                    'name' => $event->name(),
                    //'created_at' => new \DateTimeImmutable('now', new \DateTimeZone('UTC'))
                ]);

                $state['count_registered']++;

                return $state;
            },

            'customer-email-changed' => function (array $state, CustomerEmailChanged $event): void {
                $this->readModel()->stack('update', $event->aggregateRootId(), [
                    'email' => $event->newEmail()->getValue()
                ]);
            }
        ];
    }
}
