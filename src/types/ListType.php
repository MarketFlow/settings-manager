<?php

namespace MarketFlow\SettingsManager\types;

use MarketFlow\SettingsManager\interfaces\TypeInterface;

class ListType implements TypeInterface
{
    protected $type;

    public function __construct(TypeInterface $type)
    {
        $this->type = $type;
    }
    
    public function merge(array $values)
    {
        return $values[0];
    }

    public function serialize($value) : string
    {
        return json_encode(array_map(function($value) {
            return $this->type->serialize($value);
        }, $value));
    }

    public function validate($value) : bool
    {
        return array_reduce($value, function($carry, $value) {
            return $carry && $this->type->validate($value);
        }, true);
    }

    public function unserialize($value)
    {
        return array_map(function($value) {
            return $this->type->unserialize($value);
        }, json_decode($value, true));
    }

}