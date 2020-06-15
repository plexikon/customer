<?php
declare(strict_types=1);

namespace App\Providers;

use App\Infrastructure\Repository\ChronicleBankAccountRepository;
use App\Model\BankAccount\Repository\BankAccountList;
use App\Projection\Streams;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Plexikon\Chronicle\Provider\ChronicleRepositoryManager;
use Plexikon\Chronicle\Provider\ChronicleSnapshotManager;
use Plexikon\Chronicle\Support\Contracts\Snapshot\SnapshotStore;

final class BankAccountServiceProvider extends ServiceProvider
{
    public const SNAPSHOT_SERVICE_ID = 'snapshots.bank_account';

    public function register(): void
    {
        $this->app->singleton(BankAccountList::class, function (Application $app): BankAccountList {
            $repository = $app->get(ChronicleRepositoryManager::class)
                ->createRepository(Streams::BANK_ACCOUNT);

            return new ChronicleBankAccountRepository($repository);
        });

        $this->app->singleton(self::SNAPSHOT_SERVICE_ID, function (Application $app): SnapshotStore {
            return $app->get(ChronicleSnapshotManager::class)
                ->createSnapshotStore('pgsql');
        });
    }

    public function provides(): array
    {
        return [
            BankAccountList::class,
           self::SNAPSHOT_SERVICE_ID
        ];
    }
}
