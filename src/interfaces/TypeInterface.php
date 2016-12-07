<?php

namespace MarketFlow\SettingsManager\interfaces;

interface TypeInterface
{
    public function merge($values);

    public function serialize($value);

    public function validate($value);

    public function unserialize($value);
}