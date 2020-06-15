<?php
declare(strict_types=1);

namespace App\Console;

use App\Model\BankAccount\BankAccountId;
use App\Model\BankAccount\Command\DepositMoney;
use App\Model\BankAccount\Command\WithdrawMoney;
use App\Model\BankAccount\CreditOperation;
use App\Model\BankAccount\DebitOperation;
use App\Model\BankAccount\Exception\InsufficientFund;
use App\Model\BankAccount\Query\GetPaginatedBankAccounts;
use App\Model\Customer\Command\RegisterCustomer;
use App\Projection\BankAccount\BankAccountModel;
use Faker\Factory;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
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

        while ($count !== 0) {
            $this->registerCustomer();
            $count--;
        }

        $this->info("$numCustomers Customers registered");

        $this->makeDeposit($numCustomers);

        $this->info("Money deposit/withdrawn to/from accounts");
    }

    private function makeDeposit(int $numCustomers): void
    {
        $this->getPaginatedBankAccounts($numCustomers)->each(function (BankAccountModel $model): void {

            $this->creditBankAccount($model->getId());

            $i = 5;
            while ($i !== 0) {
                try {
                    $this->debitBankAccount($model->getId());

                } catch (InsufficientFund $exception) {
                    $this->warn($exception->getMessage());
                    break;
                }
                $i--;
            }
        });
    }

    private function registerCustomer(): void
    {
        $faker = Factory::create();

        $this->publisher->publishCommand(
            RegisterCustomer::withData(
                $faker->uuid, $faker->email, $faker->lastName
            )
        );
    }

    private function getPaginatedBankAccounts(int $limit = 50): Collection
    {
        return $this->publisher->publishQuery(
            new GetPaginatedBankAccounts($limit)
        )->getCollection();
    }

    private function creditBankAccount(BankAccountId $bankAccountId): void
    {
        $faker = Factory::create();

        $this->publisher->publishCommand(
            DepositMoney::toBankAccount(
                $bankAccountId->toString(),
                array_rand(array_flip(CreditOperation::getValues()), 1),
                $faker->randomFloat(2, 100, 5000)
            )
        );
    }

    private function debitBankAccount(BankAccountId $bankAccountId): void
    {
        $faker = Factory::create();

        $this->publisher->publishCommand(
            WithdrawMoney::fromBankAccount(
                $bankAccountId->toString(),
                array_rand(array_flip(DebitOperation::getValues()), 1),
                $faker->randomFloat(2, 10, 1000)
            )
        );
    }
}
