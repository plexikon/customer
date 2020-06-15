<?php
declare(strict_types=1);

namespace App\Model\BankAccount;

use App\Model\Value;
use Assert\Assert;

final class Amount implements Value
{
    private float $amount;

    protected function __construct(float $amount)
    {
        $this->amount = $amount;
    }

    public static function fromPositive(float $amount): self
    {
        Assert::that($amount, 'Invalid amount')
            ->greaterThan(0)
            ->float();

        return new self($amount);
    }

    public static function fromNegative(float $amount): self
    {
        Assert::that($amount, 'Invalid amount')
            ->lessThan(0)
            ->float();

        return new self($amount);
    }

    public function getValue(): float
    {
        return $this->amount;
    }

    public function sameValueAs(Value $aValue): bool
    {
        return $aValue instanceof $this
            && $this->getValue() === $aValue->getValue();
    }
}
