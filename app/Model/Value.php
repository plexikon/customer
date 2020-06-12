<?php

namespace App\Model;

interface Value
{
    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param Value $aValue
     * @return bool
     */
    public function sameValueAs(Value $aValue): bool;
}
