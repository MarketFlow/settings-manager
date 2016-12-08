<?php

namespace MarketFlow\SettingsManager\interfaces;

interface TypeInterface
{
    public function merge(array $values);

    public function serialize($value): string;

    public function validate($value): bool;

    public function unserialize($value);
}