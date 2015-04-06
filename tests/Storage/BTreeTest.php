<?php

/*
 * BTree Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Db;
use ZataBase\Helper\ArrayToObject;
use ZataBase\Storage\BTree;
use ZataBase\Storage\BTree\Node\Element;
use ZataBase\Tests\UnitUtils;

class BTreeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \ZataBase\Db
     */
    protected $db;
    /**
     * @var \ZataBase\Storage\BTree
     */
    protected $btree;

    protected function setUp()
    {
        $this->db = new Db(new ArrayToObject([
            "databaseDir" => __DIR__ . "/../database",
        ]));

        file_put_contents(__DIR__ . "/../database/index",
            "node,1                   ,                                                           " . PHP_EOL
            . "2,15                  ,28                  ,1                   ,2                   " . PHP_EOL
            . "                                                                                     " . PHP_EOL
            . "                                                                                     " . PHP_EOL
            . "                                                                                     " . PHP_EOL
            . "node,2                   ,0                                                          " . PHP_EOL
            . "2,5                   ,8                   ,3                   ,4                   " . PHP_EOL
            . "2,10                  ,18                  ,4                   ,5                   " . PHP_EOL
            . "                                                                                     " . PHP_EOL
            . "                                                                                     " . PHP_EOL
            . "node,1                   ,0                                                          " . PHP_EOL
            . "2,20                  ,38                  ,6                   ,7                   " . PHP_EOL
            . "                                                                                     " . PHP_EOL
            . "                                                                                     " . PHP_EOL
            . "                                                                                     " . PHP_EOL
            . "node,4                   ,1                                                          " . PHP_EOL
            . "2,1                   ,0                   ,                    ,                    " . PHP_EOL
            . "2,2                   ,2                   ,                    ,                    " . PHP_EOL
            . "2,3                   ,4                   ,                    ,                    " . PHP_EOL
            . "2,4                   ,6                   ,                    ,                    " . PHP_EOL
            . "node,4                   ,1                                                          " . PHP_EOL
            . "2,6                   ,10                  ,                    ,                    " . PHP_EOL
            . "2,7                   ,12                  ,                    ,                    " . PHP_EOL
            . "2,8                   ,14                  ,                    ,                    " . PHP_EOL
            . "2,9                   ,16                  ,                    ,                    " . PHP_EOL
            . "node,4                   ,1                                                          " . PHP_EOL
            . "2,11                  ,20                  ,                    ,                    " . PHP_EOL
            . "2,12                  ,22                  ,                    ,                    " . PHP_EOL
            . "2,13                  ,24                  ,                    ,                    " . PHP_EOL
            . "2,14                  ,26                  ,                    ,                    " . PHP_EOL
            . "node,4                   ,2                                                          " . PHP_EOL
            . "2,16                  ,30                  ,                    ,                    " . PHP_EOL
            . "2,17                  ,32                  ,                    ,                    " . PHP_EOL
            . "2,18                  ,34                  ,                    ,                    " . PHP_EOL
            . "2,19                  ,36                  ,                    ,                    " . PHP_EOL
            . "node,3                   ,2                                                          " . PHP_EOL
            . "2,21                  ,40                  ,                    ,                    " . PHP_EOL
            . "2,22                  ,42                  ,                    ,                    " . PHP_EOL
            . "2,23                  ,44                  ,                    ,                    " . PHP_EOL
            . "                                                                                     " . PHP_EOL);

        $this->btree = new BTree('index', 'data');

        foreach (range('a', 'w') as $row) {
            $this->btree->getData()->appendcsv([$row,]);
        }
    }

    protected function tearDown()
    {
        UnitUtils::deleteDir(__DIR__ . "/../database");
    }

    /**
     * @covers            \ZataBase\Storage\BTree::__construct
     * @uses              \ZataBase\Storage\BTree
     */
    public function testConstruct()
    {
        $this->assertInstanceOf('ZataBase\Storage\BTree', $this->btree);
        $this->assertInstanceOf('ZataBase\Helper\FileHandler', $this->btree->getIndex());
        $this->assertInstanceOf('ZataBase\Helper\FileHandler', $this->btree->getData());
        $this->assertEquals(Element::KEY_INT, $this->btree->getKeyType());
        $this->assertEquals(strlen(str_pad('', 85) . PHP_EOL) * 5, $this->btree->getNodeLength());
    }

    /**
     * @covers            \ZataBase\Storage\BTree::find
     * @covers            \ZataBase\Storage\BTree::locate
     * @uses              \ZataBase\Storage\BTree
     */
    public function testFind()
    {
        foreach (range(1, 23) as $row) {
            $this->assertEquals([chr(96 + $row),], $this->btree->find($row));
        }
    }

    /**
     * @covers            \ZataBase\Storage\BTree::locate
     * @uses              \ZataBase\Storage\BTree
     */
    public function testLocatePath()
    {
        $this->assertEquals([0, 2, 7,], UnitUtils::callMethod($this->btree, 'locate', [0, 24,])->getPath());
    }

    /**
     * @covers            \ZataBase\Storage\BTree::getParent
     * @uses              \ZataBase\Storage\BTree
     */
    public function testGetParent()
    {

        $this->assertEquals(
            "node,1                   ,0                                                          " . PHP_EOL
            . "2,20                  ,38                  ,6                   ,7                   ",
            UnitUtils::callMethod($this->btree, 'getParent', [$this->btree->find(24),])->toString());
    }

    /**
     * @covers            \ZataBase\Storage\BTree::getFirstNode
     * @uses              \ZataBase\Storage\BTree
     */
    public function testGetFirstNode()
    {
        $this->assertEquals(
            "node,4                   ,1                                                          " . PHP_EOL
            . "2,1                   ,0                   ,                    ,                    " . PHP_EOL
            . "2,2                   ,2                   ,                    ,                    " . PHP_EOL
            . "2,3                   ,4                   ,                    ,                    " . PHP_EOL
            . "2,4                   ,6                   ,                    ,                    ",
            $this->btree->getFirstNode()->toString());
    }

    /**
     * @covers            \ZataBase\Storage\BTree::getLastNode
     * @uses              \ZataBase\Storage\BTree
     */
    public function testGetLastNode()
    {
        $this->assertEquals(
            "node,3                   ,2                                                          " . PHP_EOL
            . "2,21                  ,40                  ,                    ,                    " . PHP_EOL
            . "2,22                  ,42                  ,                    ,                    " . PHP_EOL
            . "2,23                  ,44                  ,                    ,                    ",
            $this->btree->getLastNode()->toString());
    }

    /**
     * @covers            \ZataBase\Storage\BTree::find
     * @uses              \ZataBase\Storage\BTree
     * @expectedException Exception
     * @expectedExceptionMessage The provided Key: '15' must be unique.
     */
    public function testInsertException()
    {
        $this->btree->insertIndex([15, 48,]);
    }

    /**
     * @covers            \ZataBase\Storage\BTree::insertIndex
     * @uses              \ZataBase\Storage\BTree
     */
    public function testInsertIndexNew()
    {
        $this->btree->getIndex()->ftruncate(0);
        $this->btree->getData()->ftruncate(0);
        $this->btree->getData()->appendcsv(['a',]);
        $this->btree->insertIndex([1, 0,]);

        $this->assertEquals(['a',], $this->btree->find(1));
    }

    /**
     * @covers            \ZataBase\Storage\BTree::insertIndex
     * @uses              \ZataBase\Storage\BTree
     */
    public function testInsertIndexMultipleWithRoom()
    {
        $this->btree->getIndex()->ftruncate(0);
        $this->btree->getData()->ftruncate(0);
        $this->btree->getData()->appendcsv(['a',]);
        $this->btree->getData()->appendcsv(['b',]);
        $this->btree->getData()->appendcsv(['c',]);
        $this->btree->getData()->appendcsv(['d',]);
        $this->btree->insertIndex([1, 0,]);
        $this->btree->insertIndex([2, 2,]);
        $this->btree->insertIndex([3, 4,]);
        $this->btree->insertIndex([4, 6,]);

        $this->assertEquals(['a',], $this->btree->find(1));
        $this->assertEquals(['b',], $this->btree->find(2));
        $this->assertEquals(['c',], $this->btree->find(3));
        $this->assertEquals(['d',], $this->btree->find(4));
    }

    /**
     * @covers            \ZataBase\Storage\BTree::insertIndex
     * @uses              \ZataBase\Storage\BTree
     */
    public function testInsertIndexWithRoom()
    {
        $this->btree->getData()->appendcsv(['x',]);
        $this->btree->insertIndex([24, 46,]);
        $this->assertEquals(['x',], $this->btree->find(24));
        $this->testFind();
    }

    /**
     * @covers            \ZataBase\Storage\BTree::insert
     * @uses              \ZataBase\Storage\BTree
     */
    public function testInsert()
    {
        $this->btree->getIndex()->ftruncate(0);
        $this->btree->getData()->ftruncate(0);

        $this->btree->insert(['a',]);
        $this->assertEquals(['a',], $this->btree->find(1));
        $this->btree->insert(['b',], 2);
        $this->assertEquals(['b',], $this->btree->find(2));
        $this->btree->insert(['c',]);
        $this->assertEquals(['c',], $this->btree->find(3));
        $this->btree->insert(['d',]);
        $this->assertEquals(['d',], $this->btree->find(4));
    }

    /**
     * @covers            \ZataBase\Storage\BTree::splitNode
     * @uses              \ZataBase\Storage\BTree
     */
    public function testSplitNode()
    {
        $this->btree->insert(['x',]);

        $nodeParts = $this->btree->splitNode(new BTree\Node([
            new Element(2, 21, 40),
            new Element(2, 22, 42),
            new Element(2, 23, 44),
            new Element(2, 24, 46),
        ], 2, [0, 2, 7,]), new Element(2, 25, 48));

        $this->assertEquals(new BTree\Node([
            new Element(2, 21, 40),
            new Element(2, 22, 42),
        ], 2, [0, 2, 7,]), $nodeParts[0]);

        $this->assertEquals(new Element(2, 23, 44, 7, 8), $nodeParts[1]);

        $this->assertEquals(new BTree\Node([
            new Element(2, 24, 46),
            new Element(2, 25, 48),
        ], 2, [0, 2, 8,]), $nodeParts[2]);
    }

    /**
     * @covers            \ZataBase\Storage\BTree::splitRoot
     * @uses              \ZataBase\Storage\BTree
     */
    public function testSplitRoot()
    {
        $this->btree->getIndex()->ftruncate(0);
        $this->btree->getData()->ftruncate(0);

        $this->btree->insert(['a',]);
        $this->btree->insert(['b',]);
        $this->btree->insert(['c',]);
        $this->btree->insert(['d',]);

        $this->btree->splitRoot(new Element(2, 5, 8));

        $this->assertEquals(
            "node,1                   ,                                                           " . PHP_EOL
            . "2,3                   ,4                   ,1                   ,2                   " . PHP_EOL
            . "                                                                                     " . PHP_EOL
            . "                                                                                     " . PHP_EOL
            . "                                                                                     " . PHP_EOL
            . "node,2                   ,0                                                          " . PHP_EOL
            . "2,1                   ,0                   ,                    ,                    " . PHP_EOL
            . "2,2                   ,2                   ,                    ,                    " . PHP_EOL
            . "                                                                                     " . PHP_EOL
            . "                                                                                     " . PHP_EOL
            . "node,2                   ,0                                                          " . PHP_EOL
            . "2,4                   ,6                   ,                    ,                    " . PHP_EOL
            . "2,5                   ,8                   ,                    ,                    " . PHP_EOL
            . "                                                                                     " . PHP_EOL
            . "                                                                                     " . PHP_EOL, file_get_contents(__DIR__ . '/../database/index'));
    }

    /**
     * @covers            \ZataBase\Storage\BTree::insertIndex
     * @uses              \ZataBase\Storage\BTree
     */
    public function testInsertIndexWithoutRoom()
    {
        $this->btree->getData()->appendcsv(['x',]);
        $this->btree->insertIndex([24, 46,]);

        $this->btree->getData()->appendcsv(['y',]);
        $this->btree->insertIndex([25, 48,]);
        echo PHP_EOL . file_get_contents(__DIR__ . "/../database/index");

        $this->assertEquals(['y',], $this->btree->find(25));
        $this->testFind();
    }

    /**
     * @covers            \ZataBase\Storage\BTree::insertIndex
     * @uses              \ZataBase\Storage\BTree
     */
    public function testInsertNewRoot()
    {
        $this->btree->getIndex()->ftruncate(0);
        $this->btree->getData()->ftruncate(0);

        foreach (range(1, 26) as $row) {
            $this->btree->insert([chr(96 + $row),]);
            echo chr(96 + $row);
            echo $row . '--------------' . PHP_EOL . file_get_contents(__DIR__ . "/../database/index") . PHP_EOL;
            foreach (range(1, $row) as $find) {
                echo $find . ',';
                $this->assertEquals([chr(96 + $find),], $this->btree->find($find));
            }
        }
    }

    /**
     * @covers            \ZataBase\Storage\BTree::insertIndex
     * @uses              \ZataBase\Storage\BTree
     */
    public function testInsertNewRootLots()
    {
        $this->btree->getIndex()->ftruncate(0);
        $this->btree->getData()->ftruncate(0);

        foreach (range(1, 25) as $row) {
            $this->btree->insert([chr(96 + $row),]);
        }
        //echo file_get_contents(__DIR__ . "/../database/index");
        //print_r($this->btree->find(1));

        foreach (range(1, 25) as $row) {
            //echo chr(96 + $row) . PHP_EOL;
            $this->assertEquals([chr(96 + $row),], $this->btree->find($row));
        }
    }

}
