<?php
declare(strict_types=1);

namespace App\Model;

use App\Model\Value;
use MabeEnum\EnumSerializableTrait;

class Enum extends \MabeEnum\Enum implements \Serializable, Value
{
    use EnumSerializableTrait;

    public function sameValueAs(Value $object): bool
    {
        return $this->is($object);
    }

    public function toString(): string
    {
        return $this->getName();
    }
}
