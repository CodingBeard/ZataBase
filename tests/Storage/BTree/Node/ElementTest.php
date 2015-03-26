<?php

/*
 * element Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Storage\BTree\Node\Element;
use ZataBase\Tests\UnitUtils;

class ElementTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers            \ZataBase\Storage\BTree\Node\Element::__construct
     * @uses              \ZataBase\Storage\BTree\Node\Element
     */
    public function testConstruct()
    {
        $element = new Element([Element::KEY_INT, 1, 2]);

        $this->assertInstanceOf('ZataBase\Storage\BTree\Node\Element', $element);

        $this->assertEquals(1, $element->getKey());
        $this->assertEquals(2, $element->getByte());
        $this->assertEquals(null, $element->getLess());
        $this->assertEquals(null, $element->getMore());
        $this->assertEquals(false, $element->getHasChildren());


        $element = new Element([Element::KEY_INT, 1, 2, 3]);

        $this->assertEquals(1, $element->getKey());
        $this->assertEquals(2, $element->getByte());
        $this->assertEquals(3, $element->getLess());
        $this->assertEquals(null, $element->getMore());
        $this->assertEquals(true, $element->getHasChildren());


        $element = new Element([Element::KEY_INT, 1, 2, 3, 4]);

        $this->assertEquals(1, $element->getKey());
        $this->assertEquals(2, $element->getByte());
        $this->assertEquals(3, $element->getLess());
        $this->assertEquals(4, $element->getMore());
        $this->assertEquals(true, $element->getHasChildren());
    }

    /**
     * @covers            \ZataBase\Storage\BTree\Node\Element::getType
     * @uses              \ZataBase\Storage\BTree\Node\Element
     */
    public function testGetType()
    {
        $element = new Element([Element::KEY_INT, 1, 2]);
        $this->assertEquals(Element::KEY_INT, UnitUtils::callMethod($element, 'getType'));


        $element = new Element([Element::KEY_STRING, 'a', 2, 3]);
        $this->assertEquals(Element::KEY_STRING, UnitUtils::callMethod($element, 'getType'));


        $element = new Element([Element::KEY_DATE, '2015-01-01', 2, 3, 4]);
        $this->assertEquals(Element::KEY_DATE, UnitUtils::callMethod($element, 'getType'));
    }

    /**
     * @covers            \ZataBase\Storage\BTree\Node\Element::distance
     * @uses              \ZataBase\Storage\BTree\Node\Element
     */
    public function testDistance()
    {
        $element = new Element([Element::KEY_INT, 10, 2]);
        $this->assertEquals(2, $element->distance(12));
        $this->assertEquals(-2, $element->distance(8));


        $element = new Element([Element::KEY_STRING, 'j', 2, 3]);
        $this->assertEquals(2, $element->distance('l'));
        $this->assertEquals(-2, $element->distance('h'));


        $element = new Element([Element::KEY_DATE, '2015-01-10', 2, 3, 4]);
        $this->assertEquals(2 * 24 * 60 * 60, $element->distance('2015-01-12'));
        $this->assertEquals(-2 * 24 * 60 * 60, $element->distance('2015-01-08'));
    }

    /**
     * @covers            \ZataBase\Storage\BTree\Node\Element::toArray
     * @uses              \ZataBase\Storage\BTree\Node\Element
     */
    public function testToArray()
    {
        $element = new Element([Element::KEY_INT, 1, 2]);
        $this->assertEquals([Element::KEY_INT, 1, 2], $element->toArray());


        $element = new Element([Element::KEY_STRING, 'a', 2, 3]);
        $this->assertEquals([Element::KEY_STRING, 'a', 2, 3], $element->toArray());


        $element = new Element([Element::KEY_DATE, '2015-01-01', 2, 3, 4]);
        $this->assertEquals([Element::KEY_DATE, '2015-01-01', 2, 3, 4], $element->toArray());
    }

}
