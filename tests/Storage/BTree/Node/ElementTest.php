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

use ZataBase\Helper\Csv;
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
        $element = new Element(Element::KEY_INT, str_pad(1, 20), str_pad(2, 20));

        $this->assertInstanceOf('ZataBase\Storage\BTree\Node\Element', $element);

        $this->assertEquals(1, $element->getKey());
        $this->assertEquals(2, $element->getByte());
        $this->assertEquals(null, $element->getLess());
        $this->assertEquals(null, $element->getMore());
        $this->assertEquals(false, $element->getHasChildren());


        $element = new Element(Element::KEY_DATE, '2015-01-01', str_pad(2, 20), str_pad(3, 20));

        $this->assertEquals('2015-01-01', $element->getKey());
        $this->assertEquals(2, $element->getByte());
        $this->assertEquals(3, $element->getLess());
        $this->assertEquals(null, $element->getMore());
        $this->assertEquals(true, $element->getHasChildren());


        $element = new Element(Element::KEY_DATETIME, '2015-01-01 00:00:00', str_pad(2, 20), str_pad(3, 20), str_pad(4, 20));

        $this->assertEquals('2015-01-01 00:00:00', $element->getKey());
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
        $element = new Element(Element::KEY_INT, str_pad(1, 20), str_pad(2, 20));
        $this->assertEquals(Element::KEY_INT, UnitUtils::callMethod($element, 'getType'));


        $element = new Element(Element::KEY_DATE, '2015-01-01', str_pad(2, 20), str_pad(3, 20), str_pad(4, 20));
        $this->assertEquals(Element::KEY_DATE, UnitUtils::callMethod($element, 'getType'));
    }

    /**
     * @covers            \ZataBase\Storage\BTree\Node\Element::distance
     * @uses              \ZataBase\Storage\BTree\Node\Element
     */
    public function testDistance()
    {
        $element = new Element(Element::KEY_INT, str_pad(10, 20), str_pad(2, 20));
        $this->assertEquals(2, $element->distance(12));
        $this->assertEquals(-2, $element->distance(8));


        $element = new Element(Element::KEY_DATE, '2015-01-10', str_pad(2, 20), str_pad(3, 20), str_pad(4, 20));
        $this->assertEquals(2 * 24 * 60 * 60, $element->distance('2015-01-12'));
        $this->assertEquals(-2 * 24 * 60 * 60, $element->distance('2015-01-08'));


        $element = new Element(Element::KEY_DATETIME, '2015-01-01 10:00:00', str_pad(2, 20), str_pad(3, 20), str_pad(4, 20));
        $this->assertEquals(2 * 60 * 60, $element->distance('2015-01-01 12:00:00'));
        $this->assertEquals(-2 * 60 * 60, $element->distance('2015-01-01 08:00:00'));
    }

    /**
     * @covers            \ZataBase\Storage\BTree\Node\Element::toArray
     * @uses              \ZataBase\Storage\BTree\Node\Element
     */
    public function testToString()
    {
        $element = new Element(Element::KEY_INT, str_pad(1, 20), str_pad(2, 20));
        $this->assertEquals(Csv::arrayToCsv([Element::KEY_INT, str_pad(1, 20), str_pad(2, 20), str_pad('', 20), str_pad('', 20)]), $element->toString());


        $element = new Element(Element::KEY_DATE, '2015-01-01', str_pad(2, 20), str_pad(3, 20), str_pad(4, 20));
        $this->assertEquals(Csv::arrayToCsv([Element::KEY_DATE, '2015-01-01', str_pad(2, 20), str_pad(3, 20), str_pad(4, 20)]), $element->toString());


        $element = new Element(Element::KEY_DATETIME, '2015-01-01 00:00:00', str_pad(2, 20), str_pad(3, 20), str_pad(4, 20));
        $this->assertEquals(Csv::arrayToCsv([Element::KEY_DATETIME, '2015-01-01 00:00:00', str_pad(2, 20), str_pad(3, 20), str_pad(4, 20)]), $element->toString());
    }

}
