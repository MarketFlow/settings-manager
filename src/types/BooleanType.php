<?php

namespace MarketFlow\SettingsManager\types;

use MarketFlow\SettingsManager\interfaces\TypeInterface;

class BooleanType implements TypeInterface
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
        return is_bool($value);
    }

    public function unserialize($value)
    {
        return (bool) $value;
    }

}