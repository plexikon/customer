<?php
declare(strict_types=1);

namespace App\Console;

use App\Model\Customer\Command\RegisterCustomer;
use Faker\Factory;
use Illuminate\Console\Command;
use Plexikon\Reporter\Support\Publisher\LazyPublisher;

final class AppSeedCommand extends Command
{
    protected $signature = 'app:seed-customer {num}';

    protected LazyPublisher $publisher;

    public function __construct(LazyPublisher $lazyPublisher)
    {
        parent::__construct();

        $this->publisher = $lazyPublisher;
    }

    public function handle(): void
    {
        $count = $numCustomers = (int)$this->argument('num');
        $faker = Factory::create();

        while ($count !== 0) {
            $this->publisher->publishCommand(
                RegisterCustomer::withData(
                    $faker->uuid, $faker->email, $faker->lastName
                )
            );
            $count--;
        }

        $this->info("$numCustomers Customers registered");
    }
}
