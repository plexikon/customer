<?php
declare(strict_types=1);

namespace App\Console\ReadModel;

use App\Model\Customer\Customer;
use App\Projection\Streams;
use Illuminate\Console\Command;
use Plexikon\Chronicle\Provider\SnapshotModelProjectionManager;
use Plexikon\Chronicle\Support\Snapshot\SnapshotStreamProjection;

final class CustomerSnapshotProjectionCommand extends Command
{
    protected $signature = 'app:snapshot-customer';

    public function handle(): void
    {
        $projection = $this->createCustomerStreamProjection();

        $projection->run(true);
    }

    protected function createCustomerStreamProjection(): SnapshotStreamProjection
    {
        return $this->getLaravel()
            ->get(SnapshotModelProjectionManager::class)
            ->createSnapshotProjection(Streams::CUSTOMER, [Customer::class]);
    }
}
