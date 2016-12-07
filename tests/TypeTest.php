<?php

namespace MarketFlow\SettingsManager\tests;

use MarketFlow\SettingsManager\types\StringType;

class TypeTest extends \PHPUnit_Framework_TestCase
{
    public function testSerializeUnserialize()
    {
        $value = 'test';

        $type = new StringType();

        $this->assertEquals($value, $type->unserialize($type->serialize($value)));
    }

    public function testValidate()
    {
        $value1 = 'test';
        $value2 = json_decode('{}');

        $type = new StringType();

        $this->assertTrue($type->validate($value1));
        $this->assertFalse($type->validate($value2));
    }

    public function testMerge()
    {
        $values = [
            'test1',
            'test2',
            'test3'
        ];

        $type = new StringType();

        $this->assertEquals($type->merge($values), $values[0]);
    }
}