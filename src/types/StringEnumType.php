<?php

namespace MarketFlow\SettingsManager\types;

use MarketFlow\SettingsManager\interfaces\TypeInterface;

class StringEnumType implements TypeInterface
{
    /**
     * @var string[]
     */
    private $values = [];

    /**
     * StringEnumType constructor.
     * @param string[] $values The valid values for this enumeration type.
     */
    public function __construct(array $values)
    {
        foreach($values as $value) {
            $this->values[] = (string) $value;
        }
    }


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
        return is_string($value) && in_array($value, $this->values, true);
    }

    public function unserialize($value)
    {
        return $value;
    }

}