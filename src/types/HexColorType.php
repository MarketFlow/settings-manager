<?php

namespace MarketFlow\SettingsManager\types;

class HexColorType extends StringType
{
    public function validate($value) : bool
    {
        return preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value);
    }
}