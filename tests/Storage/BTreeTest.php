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
            "databaseDir" => __DIR__ . "/../database"
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
            . "node,4                   ,2                                                          " . PHP_EOL
            . "2,21                  ,40                  ,                    ,                    " . PHP_EOL
            . "2,22                  ,42                  ,                    ,                    " . PHP_EOL
            . "2,23                  ,44                  ,                    ,                    " . PHP_EOL
            . "                                                                                     " . PHP_EOL);

        $this->btree = new BTree('index', 'data');

        foreach (range('a', 'w') as $row) {
            $this->btree->getData()->appendcsv([$row]);
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
            $this->assertEquals([chr(96 + $row)], $this->btree->find($row));
        }
    }

    /**
     * @covers            \ZataBase\Storage\BTree::locate
     * @uses              \ZataBase\Storage\BTree
     */
    public function testLocatePath()
    {
        $this->assertEquals([0, 2, 7], UnitUtils::callMethod($this->btree, 'locate', [0, 24])->getPath());
    }

    /**
     * @covers            \ZataBase\Storage\BTree::locate
     * @uses              \ZataBase\Storage\BTree
     */
    public function testgetParent()
    {

        $this->assertEquals(
              "node,1                   ,0                                                          " . PHP_EOL
            . "2,20                  ,38                  ,6                   ,7                   ",
            UnitUtils::callMethod($this->btree, 'getParent', [$this->btree->find(24)])->toString());
    }

    /**
     * @covers            \ZataBase\Storage\BTree::find
     * @uses              \ZataBase\Storage\BTree
     * @expectedException Exception
     * @expectedExceptionMessage The provided Key: '15' must be unique.
     */
    public function testInsertException()
    {
        $this->btree->insertIndex([15, 48]);
    }

    /**
     * @covers            \ZataBase\Storage\BTree::insertIndex
     * @uses              \ZataBase\Storage\BTree
     */
    public function testInsertIndexNew()
    {
        $this->btree->getIndex()->ftruncate(0);
        $this->btree->getData()->ftruncate(0);
        $this->btree->getData()->appendcsv(['a']);
        $this->btree->insertIndex([1, 0]);

        $this->assertEquals(['a'], $this->btree->find(1));
    }

    /**
     * @covers            \ZataBase\Storage\BTree::insertIndex
     * @uses              \ZataBase\Storage\BTree
     */
    public function testInsertIndexMultipleWithRoom()
    {
        $this->btree->getIndex()->ftruncate(0);
        $this->btree->getData()->ftruncate(0);
        $this->btree->getData()->appendcsv(['a']);
        $this->btree->getData()->appendcsv(['b']);
        $this->btree->getData()->appendcsv(['c']);
        $this->btree->getData()->appendcsv(['d']);
        $this->btree->insertIndex([1, 0]);
        $this->btree->insertIndex([2, 2]);
        $this->btree->insertIndex([3, 4]);
        $this->btree->insertIndex([4, 6]);

        $this->assertEquals(['a'], $this->btree->find(1));
        $this->assertEquals(['b'], $this->btree->find(2));
        $this->assertEquals(['c'], $this->btree->find(3));
        $this->assertEquals(['d'], $this->btree->find(4));
    }

    /**
     * @covers            \ZataBase\Storage\BTree::insertIndex
     * @uses              \ZataBase\Storage\BTree
     */
    public function testInsertIndexWithRoom()
    {
        $this->btree->getData()->appendcsv(['x']);
        $this->btree->insertIndex([24, 46]);
        $this->assertEquals(['x'], $this->btree->find(24));
    }

    /**
     * @covers            \ZataBase\Storage\BTree::insertIndex
     * @uses              \ZataBase\Storage\BTree
     */
    public function testInsertIndexWithoutRoom()
    {
        $this->btree->getData()->appendcsv(['x']);
        $this->btree->insertIndex([24, 46]);

        $this->btree->getData()->appendcsv(['y']);
        $this->btree->insertIndex([25, 48]);

        $this->assertEquals(['y'], $this->btree->find(25));
    }

    /**
     * @covers            \ZataBase\Storage\BTree::insert
     * @uses              \ZataBase\Storage\BTree
     */
    public function testInsert()
    {
        $this->btree->getIndex()->ftruncate(0);
        $this->btree->getData()->ftruncate(0);

        $this->btree->insert(['a'], 1);
        $this->assertEquals(['a'], $this->btree->find(1));
        $this->btree->insert(['b'], 2);
        $this->assertEquals(['b'], $this->btree->find(2));
        $this->btree->insert(['c'], 3);
        $this->assertEquals(['c'], $this->btree->find(3));
        $this->btree->insert(['d'], 4);
        $this->assertEquals(['d'], $this->btree->find(4));
    }

}
