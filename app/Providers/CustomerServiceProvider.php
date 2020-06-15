<?php
declare(strict_types=1);

namespace App\Providers;

use App\Infrastructure\Repository\ChronicleCustomerRepository;
use App\Infrastructure\Service\UniqueEmailFromRead;
use App\Model\Customer\Repository\CustomerCollection;
use App\Model\Customer\Service\UniqueEmail;
use App\Projection\Streams;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Plexikon\Chronicle\Provider\ChronicleRepositoryManager;
use Plexikon\Chronicle\Provider\ChronicleSnapshotManager;
use Plexikon\Chronicle\Support\Contracts\Snapshot\SnapshotStore;

final class CustomerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public const SNAPSHOT_SERVICE_ID = 'snapshots.customer';

    public array $bindings = [
      UniqueEmail::class => UniqueEmailFromRead::class
    ];

    public function register(): void
    {
        $this->app->singleton(CustomerCollection::class, function (Application $app): CustomerCollection {
            $repository = $app->get(ChronicleRepositoryManager::class)
                ->createRepository(Streams::CUSTOMER);

            return new ChronicleCustomerRepository($repository);
        });

        $this->app->singleton(self::SNAPSHOT_SERVICE_ID, function (Application $app): SnapshotStore {
            return $app->get(ChronicleSnapshotManager::class)
                ->createSnapshotStore('pgsql');
        });
    }

    public function provides(): array
    {
        return [
            CustomerCollection::class,
            SnapshotStore::class,
            self::SNAPSHOT_SERVICE_ID
        ];
    }
}
