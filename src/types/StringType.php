<?php

namespace MarketFlow\SettingsManager\types;

use MarketFlow\SettingsManager\interfaces\TypeInterface;

class StringType implements TypeInterface
{
    public function merge($values)
    {
        return isset($values[0]) ? $values[0] : null;
    }

    public function serialize($value)
    {
        return $value;
    }

    public function validate($value)
    {
        return is_string($value);
    }

    public function unserialize($value)
    {
        return $value;
    }

}