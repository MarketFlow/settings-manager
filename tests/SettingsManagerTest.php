<?php

namespace MarketFlow\SettingsManager\tests;

use MarketFlow\SettingsManager\interfaces\SettingChainedInterface;
use UnexpectedValueException;
use MarketFlow\SettingsManager\interfaces\SettingInterface;
use MarketFlow\SettingsManager\interfaces\StorageInterface;
use MarketFlow\SettingsManager\interfaces\TypeInterface;
use MarketFlow\SettingsManager\SettingsManager;
use MarketFlow\SettingsManager\types\StringType;

class SettingsManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $storage = $this->createStorageMock();
        $sm = new SettingsManager($storage);

        $this->assertInstanceOf(SettingsManager::class, $sm);
    }

    public function testRegisterType()
    {
        $typeName = 'test';
        $type = $this->createTypeMock();
        $sm = new SettingsManager($this->createStorageMock());

        $sm->registerType($typeName, $type);
        $this->assertEquals($sm->getType($typeName), $type);
    }

    public function testGetUnknownType()
    {
        $typeName = 'test';
        $sm = new SettingsManager($this->createStorageMock());

        $this->expectException(\Exception::class);
        $sm->getType($typeName);
    }

    public function testSetApplicationValue()
    {
        $key = 'testKey';
        $value = 'testValue';

        $setting = $this->createTypeMock();
        $setting
            ->expects($this->once())
            ->method('validate');

        $serializedValue = $setting->serialize($value);

        $setting
            ->expects($this->once())
            ->method('serialize');

        $storage = $this->createStorageMock();
        $storage
            ->expects($this->once())
            ->method('setApplicationSetting')
            ->with($key, $serializedValue)
        ;

        $sm = new SettingsManager($storage);
        $sm->registerType($key, $setting);
        $sm->setApplicationSetting($key, $value);
    }

    public function testSetInvalidApplicationValue()
    {
        $key = 'testKey';
        $value = 1;

        $setting = $this->createTypeMock(false);
        $setting
            ->expects($this->once())
            ->method('validate')
        ;

        $serializedValue = $setting->serialize($value);

        $setting
            ->expects($this->never())
            ->method('serialize');

        $storage = $this->createStorageMock();
        $storage
            ->expects($this->never())
            ->method('setApplicationSetting')
            ->with($key, $serializedValue)
        ;

        $sm = new SettingsManager($storage);
        $sm->registerType($key, $setting);
        $this->expectException(UnexpectedValueException::class);
        $sm->setApplicationSetting($key, $value);
    }

    public function testGetApplicationValue()
    {
        $key = 'testKey';
        $value = 'testValue';

        $setting = $this->createTypeMock();

        $storage = $this->createStorageMock();
        $storage
            ->expects($this->once())
            ->method('getApplicationSetting')
            ->willReturn($value);
        ;

        $sm = new SettingsManager($storage);

        $sm->registerType($key, $setting);
        $this->assertEquals($value, $sm->get($key));
    }

    public function testSetContextValue()
    {
        $key = 'testKey';
        $value = 'testValue';

        $setting = $this->createTypeMock();
        $setting
            ->expects($this->once())
            ->method('validate');

        $serializedValue = $setting->serialize($value);

        $setting
            ->expects($this->once())
            ->method('serialize');

        $storage = $this->createStorageMock();
        $storage
            ->expects($this->once())
            ->method('setSetting')
            ->with($this->isType('string'), $serializedValue)
        ;

        $context = $this->createContextMock();
        $context
            ->expects($this->once())
            ->method('getSettingId')
        ;

        $sm = new SettingsManager($storage);
        $sm->registerType($key, $setting);
        $sm->setContextSetting($context, $key, $value);
    }

    public function testSetInvalidContextValue()
    {
        $key = 'testKey';
        $value = 1;

        $setting = $this->createTypeMock(false);
        $setting
            ->expects($this->once())
            ->method('validate');

        $serializedValue = $setting->serialize($value);

        $setting
            ->expects($this->never())
            ->method('serialize');

        $storage = $this->createStorageMock();
        $storage
            ->expects($this->never())
            ->method('setSetting')
            ->with($this->isType('string'), $serializedValue)
        ;

        $context = $this->createContextMock();
        $context
            ->expects($this->never())
            ->method('getSettingId')
        ;

        $sm = new SettingsManager($storage);
        $sm->registerType($key, $setting);
        $this->expectException(UnexpectedValueException::class);
        $sm->setContextSetting($context, $key, $value);
    }

    public function testGetContextValue()
    {
        $key = 'testKey';
        $value = 'testValue';

        $setting = $this->createTypeMock();

        $context = $this->createContextMock();

        $storage = $this->createStorageMock();
        $storage
            ->expects($this->once())
            ->method('getSetting')
            ->willReturn($value);
        ;

        $sm = new SettingsManager($storage);
        
        $sm->registerType($key, $setting);
        $this->assertEquals($value, $sm->get($key, $context));
    }

    public function testSetUserValue()
    {
        $key = 'testKey';
        $value = 'testValue';

        $setting = $this->createTypeMock();
        $setting
            ->expects($this->once())
            ->method('validate');

        $serializedValue = $setting->serialize($value);

        $setting
            ->expects($this->once())
            ->method('serialize');

        $storage = $this->createStorageMock();
        $storage
            ->expects($this->once())
            ->method('setUserSetting')
            ->with($this->isType('string'), $this->isType('string'), $serializedValue)
        ;

        $context = $this->createContextMock();
        $context
            ->expects($this->exactly(2))
            ->method('getSettingId')
        ;

        $sm = new SettingsManager($storage);
        $sm->registerType($key, $setting);
        $sm->setUserSetting($context, $key, $value);
    }

    public function testSetInvalidUserValue()
    {
        $key = 'testKey';
        $value = 1;

        $setting = $this->createTypeMock(false);
        $setting
            ->expects($this->once())
            ->method('validate');

        $serializedValue = $setting->serialize($value);

        $setting
            ->expects($this->never())
            ->method('serialize');

        $storage = $this->createStorageMock();
        $storage
            ->expects($this->never())
            ->method('setUserSetting')
            ->with($this->isType('string'), $serializedValue)
        ;

        $context = $this->createContextMock();
        $context
            ->expects($this->never())
            ->method('getSettingId')
        ;

        $sm = new SettingsManager($storage);
        $sm->registerType($key, $setting);
        $this->expectException(UnexpectedValueException::class);
        $sm->setUserSetting($context, $key, $value);
    }

    public function testGetUserValue()
    {
        $key = 'testKey';
        $value = 'testValue';

        $setting = $this->createTypeMock();

        $context = $this->createContextMock();

        $storage = $this->createStorageMock();
        $storage
            ->expects($this->once())
            ->method('getUserSetting')
            ->willReturn($value);
        ;

        $sm = new SettingsManager($storage);

        $sm->registerType($key, $setting);
        $this->assertEquals($value, $sm->get($key, null, $context));
    }

    private function createStorageMock()
    {
        $mock = $this->createMock(StorageInterface::class);

        return $mock;
    }

    private function createTypeMock($validateReturn = true)
    {
        $mock = $this->createMock(TypeInterface::class);

        $mock
            ->method('validate')
            ->willReturn($validateReturn);

        $mock
            ->method('serialize')
            ->willReturnArgument(0)
        ;

        $mock
            ->method('unserialize')
            ->willReturnArgument(0)
        ;

        $mock
            ->method('merge')
            ->willReturnCallback(function($value) {
                return reset($value);
            })
        ;

        return $mock;
    }

    private function createUserContextMock()
    {
        $mock = $this->createMock(SettingInterface::class);

        $mock
            ->method('getSettingId')
            ->willReturn('setting_1')
        ;

        return $mock;
    }

    private function createContextMock()
    {
        $mock = $this->createMock(SettingChainedInterface::class);

        $mock
            ->method('getSettingId')
            ->willReturn('setting_1')
        ;

        return $mock;
    }
}