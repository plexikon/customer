<?php
declare(strict_types=1);

namespace App\Model\Customer;

use App\Model\Value;
use Assert\Assertion;

final class EmailAddress implements Value
{
    private $email;

    protected function __construct(string $email)
    {
        $this->email = $email;
    }

    public static function fromString(string $email): self
    {
        Assertion::email($email, 'Email is not valid');

        return new self($email);
    }

    public function getValue(): string
    {
        return $this->email;
    }

    public function sameValueAs(Value $aValue): bool
    {
        return $aValue instanceof $this
            && $this->getValue() === $aValue->getValue();
    }
}
