<?php
declare(strict_types=1);

namespace App\Console\ReadModel;

use App\Model\BankAccount\BankAccount;
use App\Projection\Streams;
use Illuminate\Console\Command;
use Plexikon\Chronicle\Provider\SnapshotModelProjectionManager;
use Plexikon\Chronicle\Support\Snapshot\SnapshotStreamProjection;

final class BankAccountSnapshotProjectionCommand extends Command
{
    protected $signature = 'app:snapshot-bank_account';

    public function handle(): void
    {
        $projection = $this->createCustomerStreamProjection();

        $projection->run(true);
    }

    protected function createCustomerStreamProjection(): SnapshotStreamProjection
    {
        return $this->getLaravel()
            ->get(SnapshotModelProjectionManager::class)
            ->createSnapshotProjection(Streams::BANK_ACCOUNT, [BankAccount::class]);
    }
}
