<?php

namespace MarketFlow\SettingsManager\types;

use MarketFlow\SettingsManager\interfaces\TypeInterface;

class IntegerType implements TypeInterface
{
    public function merge(array $values)
    {
        return $values[0];
    }

    public function serialize($value) : string
    {
        return $value;
    }

    public function validate($value) : bool
    {
        return is_int($value);
    }

    public function unserialize($value)
    {
        return (int) $value;
    }

}