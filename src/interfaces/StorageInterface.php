<?php

namespace MarketFlow\SettingsManager\interfaces;

interface StorageInterface
{
    public function getSetting($key);

    public function setSetting($key, $value);

    public function getApplicationSetting($key);

    public function setApplicationSetting($key, $value);

    public function getUserSetting($userId, $key);

    public function setUserSetting($userId, $key, $value);
}