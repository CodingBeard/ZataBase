<?php

/*
 * Node Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Helper\FileHandler;
use ZataBase\Storage\BTree\Node;
use ZataBase\Storage\BTree\Node\Element;
use ZataBase\Tests\UnitUtils;

class NodeTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers            \ZataBase\Storage\BTree\Node::__construct
     * @uses              \ZataBase\Storage\BTree\Node
     */
    public function testConstruct()
    {
        $elements = [
            new Element(Element::KEY_INT, 1, 2),
            new Element(Element::KEY_INT, 3, 4, 5),
            new Element(Element::KEY_INT, 6, 7, 8, 9),
        ];

        $node = new Node($elements);

        $this->assertInstanceOf('ZataBase\Storage\BTree\Node', $node);
        $this->assertEquals($elements, $node->getElements());
    }

    /**
     * @covers            \ZataBase\Storage\BTree\Node::getId
     * @uses              \ZataBase\Storage\BTree\Node
     */
    public function testGetId()
    {
        $elements = [
            new Element(Element::KEY_INT, 1, 2),
        ];

        $node = new Node($elements);
        $node->setPath([0, 2, 7]);

        $this->assertEquals(7, $node->getId());
    }

    /**
     * @covers            \ZataBase\Storage\BTree\Node::load
     * @uses              \ZataBase\Storage\BTree\Node
     */
    public function testLoad()
    {
        $file = new FileHandler('Load', 'w+');

        $elements = [
            new Element(Element::KEY_INT, 1, 2),
            new Element(Element::KEY_INT, 3, 4, 5),
            new Element(Element::KEY_INT, 6, 7, 8, 9),
        ];

        $file->appendcsv(['node', str_pad(3, 20)]);

        foreach ($elements as $element) {
            $file->appendRaw($element->toString());
        }

        $file->fseek(0);
        $node = Node::load($file);

        $this->assertInstanceOf('ZataBase\Storage\BTree\Node', $node);
        $this->assertEquals($elements, $node->getElements());

        unlink('Load');
    }

    /**
     * @covers            \ZataBase\Storage\BTree\Node::count
     * @uses              \ZataBase\Storage\BTree\Node
     */
    public function testCount()
    {
        $node = new Node([
            new Element(Element::KEY_INT, 1, 2),
            new Element(Element::KEY_INT, 3, 4, 5),
            new Element(Element::KEY_INT, 6, 7, 8, 9),
        ]);

        $this->assertEquals(3, $node->count());
    }

    /**
     * @covers            \ZataBase\Storage\BTree\Node::sort
     * @uses              \ZataBase\Storage\BTree\Node
     */
    public function testSortInt()
    {
        $node = new Node([
            new Element(Element::KEY_INT, 2, 0),
            new Element(Element::KEY_INT, 0, 0),
            new Element(Element::KEY_INT, 1, 0),
        ]);

        $node->sort();

        $this->assertEquals([
            new Element(Element::KEY_INT, 0, 0),
            new Element(Element::KEY_INT, 1, 0),
            new Element(Element::KEY_INT, 2, 0),
        ], $node->getElements());
    }

    /**
     * @covers            \ZataBase\Storage\BTree\Node::sort
     * @uses              \ZataBase\Storage\BTree\Node
     */
    public function testSortDate()
    {
        $node = new Node([
            new Element(Element::KEY_DATE, '2015-01-03', 0),
            new Element(Element::KEY_DATE, '2015-01-01', 0),
            new Element(Element::KEY_DATE, '2015-01-02', 0),
        ]);

        $node->sort();

        $this->assertEquals([
            new Element(Element::KEY_DATE, '2015-01-01', 0),
            new Element(Element::KEY_DATE, '2015-01-02', 0),
            new Element(Element::KEY_DATE, '2015-01-03', 0),
        ], $node->getElements());
    }

    /**
     * @covers            \ZataBase\Storage\BTree\Node::sort
     * @uses              \ZataBase\Storage\BTree\Node
     */
    public function testSortDateTime()
    {
        $node = new Node([
            new Element(Element::KEY_DATETIME, '2015-01-01 00:00:01', 0),
            new Element(Element::KEY_DATETIME, '2015-01-01 00:00:03', 0),
            new Element(Element::KEY_DATETIME, '2015-01-01 00:00:02', 0),
        ]);

        $node->sort();

        $this->assertEquals([
            new Element(Element::KEY_DATETIME, '2015-01-01 00:00:01', 0),
            new Element(Element::KEY_DATETIME, '2015-01-01 00:00:02', 0),
            new Element(Element::KEY_DATETIME, '2015-01-01 00:00:03', 0),
        ], $node->getElements());
    }

    /**
     * @covers            \ZataBase\Storage\BTree\Node::addElement
     * @uses              \ZataBase\Storage\BTree\Node
     */
    public function testAddElement()
    {
        $node = new Node([new Element(Element::KEY_INT, 1, 2)]);

        $node->addElement(new Element(Element::KEY_INT, 3, 4, 5, 6));

        $this->assertEquals([new Element(Element::KEY_INT, 1, 2), new Element(Element::KEY_INT, 3, 4, 5, 6)], $node->getElements());
    }

    /**
     * @covers            \ZataBase\Storage\BTree\Node::removeElement
     * @uses              \ZataBase\Storage\BTree\Node
     */
    public function testRemoveElement()
    {
        $node = new Node([
            new Element(Element::KEY_INT, 1, 2),
            new Element(Element::KEY_INT, 3, 4),
            new Element(Element::KEY_INT, 5, 6),
        ]);

        $node->removeElement(3);

        $this->assertEquals([
            0 => new Element(Element::KEY_INT, 1, 2),
            2 => new Element(Element::KEY_INT, 5, 6),
        ], $node->getElements());
    }

    /**
     * @covers            \ZataBase\Storage\BTree\Node::HasKey
     * @uses              \ZataBase\Storage\BTree\Node
     */
    public function testKasKey()
    {
        $node = new Node([
            new Element(Element::KEY_INT, 1, 2),
        ]);

        $this->assertEquals(false, $node->HasKey(6));


        $node = new Node([
            new Element(Element::KEY_INT, 1, 2),
            new Element(Element::KEY_INT, 5, 6, 0, 10),
            new Element(Element::KEY_INT, 9, 10, 10, 20),
        ]);

        $this->assertEquals(new Element(Element::KEY_INT, 5, 6, 0, 10), $node->HasKey(5));
        $this->assertEquals(0, $node->HasKey(4));
        $this->assertEquals(10, $node->HasKey(6));
        $this->assertEquals(10, $node->HasKey(8));
        $this->assertEquals(20, $node->HasKey(10));
    }

    /**
     * @covers            \ZataBase\Storage\BTree\Node::getFirst
     * @uses              \ZataBase\Storage\BTree\Node
     */
    public function testGetFirst()
    {
        $node = new Node([
            new Element(Element::KEY_INT, 1, 2),
            new Element(Element::KEY_INT, 3, 4),
            new Element(Element::KEY_INT, 5, 6),
        ]);

        $this->assertEquals(new Element(Element::KEY_INT, 1, 2), $node->getFirst());
    }

    /**
     * @covers            \ZataBase\Storage\BTree\Node::getElement
     * @uses              \ZataBase\Storage\BTree\Node
     */
    public function testGetElement()
    {
        $node = new Node([
            new Element(Element::KEY_INT, 1, 2),
            new Element(Element::KEY_INT, 3, 4),
            new Element(Element::KEY_INT, 5, 6),
        ]);

        $this->assertEquals(new Element(Element::KEY_INT, 3, 4), $node->getElement(1));
    }

    /**
     * @covers            \ZataBase\Storage\BTree\Node::getLast
     * @uses              \ZataBase\Storage\BTree\Node
     */
    public function testGetLast()
    {
        $node = new Node([
            new Element(Element::KEY_INT, 1, 2),
            new Element(Element::KEY_INT, 3, 4),
            new Element(Element::KEY_INT, 5, 6),
        ]);

        $this->assertEquals(new Element(Element::KEY_INT, 5, 6), $node->getLast());
    }

    /**
     * @covers            \ZataBase\Storage\BTree\Node::toString
     * @uses              \ZataBase\Storage\BTree\Node
     */
    public function testToString()
    {
        $node = new Node([
            new Element(Element::KEY_INT, 1, 2),
            new Element(Element::KEY_INT, 3, 4),
        ]);

        $this->assertEquals(
            str_pad("node," . 2, 85)
            . PHP_EOL
            . "2," . str_pad(1, 20) . "," . str_pad(2, 20) . "," . str_pad('', 20) . "," . str_pad('', 20)
            . PHP_EOL
            . "2," . str_pad(3, 20) . "," . str_pad(4, 20) . "," . str_pad('', 20) . "," . str_pad('', 20)
            . PHP_EOL
            . Element::blankString()
            . PHP_EOL
            . Element::blankString(), $node->toString(4));
    }

}
