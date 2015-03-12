<?php

/*
 * File Handler Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Helper\ArrayToObject;

class ArrayToObjectTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers            \ZataBase\Helper\ArrayToObject::__construct
     * @uses              \ZataBase\Helper\ArrayToObject
     */
    public function testArrayToObject()
    {
        $object = new ArrayToObject([
            'one' => 'value',
            'two' => [
                'three' => 'value'
            ]
        ]);

        $this->assertInstanceOf('\ZataBase\Helper\ArrayToObject', $object);

        $this->assertEquals('value', $object->one);

        $this->assertInstanceOf('\ZataBase\Helper\ArrayToObject', $object->two);

        $this->assertEquals('value', $object->two->three);
    }
}
