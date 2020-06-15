<?php
declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Model\BankAccount\BankAccount;
use App\Model\BankAccount\BankAccountId;
use App\Model\BankAccount\Repository\BankAccountList;
use Plexikon\Chronicle\Support\Contracts\Aggregate\AggregateRepository;

final class ChronicleBankAccountRepository implements BankAccountList
{
    private AggregateRepository $aggregateRepository;

    public function __construct(AggregateRepository $aggregateRepository)
    {
        $this->aggregateRepository = $aggregateRepository;
    }

    public function get(BankAccountId $bankAccountId): ?BankAccount
    {
        /** @var BankAccount $bankAccount */
        $bankAccount = $this->aggregateRepository->retrieve($bankAccountId);

        return $bankAccount->exists() ? $bankAccount : null;
    }

    public function store(BankAccount $bankAccount): void
    {
        $this->aggregateRepository->persist($bankAccount);
    }
}
