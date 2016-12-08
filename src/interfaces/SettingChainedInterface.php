<?php

namespace MarketFlow\SettingsManager\interfaces;

interface SettingChainedInterface extends SettingInterface
{
    public function getSettingParent();
}