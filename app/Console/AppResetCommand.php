<?php
declare(strict_types=1);

namespace App\Console;

use App\Projection\Streams;
use Illuminate\Console\Command;
use Illuminate\Database\ConnectionInterface;
use Plexikon\Chronicle\Stream\Stream;
use Plexikon\Chronicle\Stream\StreamName;
use Plexikon\Chronicle\Support\Contracts\Chronicler\Chronicle;

final class AppResetCommand extends Command
{
    protected $signature = 'app:reset';

    protected array $streams = [
        Streams::CUSTOMER,
        Streams::BANK_ACCOUNT,
    ];

    private Chronicle $chronicle;
    private ConnectionInterface $connection;

    public function __construct(Chronicle $chronicle, ConnectionInterface $connection)
    {
        parent::__construct();

        $this->chronicle = $chronicle;
        $this->connection = $connection;
    }

    public function handle(): void
    {
//        if (!$this->confirm('Restart from scratch?')) {
//            $this->warn('Flush app aborted');
//        }

        $this->connection->getSchemaBuilder()->dropAllTables();

        $this->call('migrate');

        foreach ($this->streams as $stream) {
            $this->chronicle->persistFirstCommit(
                new Stream(new StreamName($stream))
            );

            $this->line("Stream $stream created");
        }

        $this->info('Done ... setup your projections');
    }
}
