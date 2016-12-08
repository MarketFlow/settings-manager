<?php

namespace MarketFlow\SettingsManager;

use Exception;
use MarketFlow\SettingsManager\interfaces\SettingChainedInterface;
use MarketFlow\SettingsManager\interfaces\SettingInterface;
use MarketFlow\SettingsManager\interfaces\StorageInterface;
use MarketFlow\SettingsManager\interfaces\TypeInterface;
use UnexpectedValueException;

/**
 * Class SettingsManager
 * @package MarketFlow\SettingsManager
 */
class SettingsManager
{
    /** @var string */
    private $separator = '|';

    /** @var StorageInterface */
    private $storage;

    /** @var TypeInterface[] */
    private $types = [];

    /**
     * SettingsManager constructor.
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param $key
     * @param SettingInterface|null $context
     * @param SettingInterface|null $userContext
     * @return mixed
     * @throws Exception
     */
    public function get($key, SettingChainedInterface $context = null, SettingInterface $userContext = null)
    {
        $result = [];
        $setting = $this->getType($key);

        if (
            isset($userContext)
            && null !== $serialized = $this->storage->getUserSetting($userContext->getSettingId(), $this->userKey($key, $userContext))
        ) {
            $result[] = $setting->unserialize($serialized);
        }

        while (isset($context)) {
            if (null !== $serialized = $this->storage->getSetting($this->contextKey($key, $context))) {
                $result[] = $setting->unserialize($serialized);
            }
            $context = $context->getSettingParent();
        }

        if (
            null !== $serialized = $this->storage->getApplicationSetting($key)
        ) {
            $result[] = $setting->unserialize($serialized);
        }

        return empty($result)
            ? null
            : ((count($result) > 1) ? $setting->merge($result) : $result[0]);
    }

    /**
     * @param $key
     * @return TypeInterface
     * @throws Exception
     */
    public function getType($key) : TypeInterface
    {
        if (isset($this->types[$key])) {
            return $this->types[$key];
        } else {
            throw new Exception('Unknown type key');
        }
    }

    /**
     * @param $name
     * @param TypeInterface $type
     */
    public function registerType($name, TypeInterface $type)
    {
        $this->types[$name] = $type;
    }

    /**
     * @param $key
     * @param $value
     * @throws Exception
     */
    public function setApplicationSetting($key, $value)
    {
        $setting = $this->getType($key);

        if ($setting->validate($value)) {
            $this->storage->setApplicationSetting($key, $setting->serialize($value));
        } else {
            throw new UnexpectedValueException('Validation failed');
        }
    }

    /**
     * @param SettingInterface $context
     * @param $key
     * @param $value
     * @throws Exception
     */
    public function setContextSetting(SettingChainedInterface $context, $key, $value)
    {
        $setting = $this->getType($key);

        if ($setting->validate($value)) {
            $this->storage->setSetting(
                $this->contextKey($key, $context),
                $setting->serialize($value)
            );
        } else {
            throw new UnexpectedValueException('Validation failed');
        }
    }

    /**
     * @param SettingInterface $userContext
     * @param $key
     * @param $value
     * @throws Exception
     */
    public function setUserSetting(SettingInterface $userContext, $key, $value)
    {
        $setting = $this->getType($key);

        if ($setting->validate($value)) {
            $this->storage->setUserSetting(
                $userContext->getSettingId(),
                $this->userKey($key, $userContext),
                $setting->serialize($value)
            );
        } else {
            throw new UnexpectedValueException('Validation failed');
        }
    }

    /**
     * @param $key
     * @param SettingInterface $userContext
     * @return string
     */
    private function userKey($key, SettingInterface $userContext) {
        return $key . $this->separator . $userContext->getSettingId();
    }

    /**
     * @param $key
     * @param SettingInterface $context
     * @return string
     */
    private function contextKey($key, SettingChainedInterface $context) {
        return $key . $this->separator . $context->getSettingId();
    }
}