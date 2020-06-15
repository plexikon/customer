<?php

namespace App\Console;

use App\Console\Query\BankAccountOfCustomerCommand;
use App\Console\ReadModel\BankAccountPersistentProjectionCommand;
use App\Console\ReadModel\BankAccountSnapshotProjectionCommand;
use App\Console\ReadModel\CustomerPersistentProjectionCommand;
use App\Console\ReadModel\CustomerSnapshotProjectionCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        AppResetCommand::class,
        AppSeedCommand::class,
        ProjectionWorkerCommand::class,

        CustomerPersistentProjectionCommand::class,
        CustomerSnapshotProjectionCommand::class,

        BankAccountPersistentProjectionCommand::class,
        BankAccountSnapshotProjectionCommand::class,

        BankAccountOfCustomerCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
