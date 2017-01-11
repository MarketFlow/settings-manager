<?php
namespace MarketFlow\SettingsManager\tests;


use MarketFlow\SettingsManager\types\StringEnumType;

class StringEnumTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testValidate()
    {
        $type = new StringEnumType([
            'a', 'b', 'c'
        ]);

        $this->assertTrue($type->validate('b'));
        $this->assertFalse($type->validate('x'));
    }
}