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
              "node,1                                                                               \n"
            . "2,15                  ,33                  ,1                   ,2                   \n"
            . "0,                    ,                    ,                    ,                    \n"
            . "0,                    ,                    ,                    ,                    \n"
            . "0,                    ,                    ,                    ,                    \n"
            . "node,2                                                                               \n"
            . "2,5                   ,8                   ,3                   ,4                   \n"
            . "2,10                  ,18                  ,4                   ,5                   \n"
            . "0,                    ,                    ,                    ,                    \n"
            . "0,                    ,                    ,                    ,                    \n"
            . "node,1                                                                               \n"
            . "2,20                  ,48                  ,6                   ,7                   \n"
            . "0,                    ,                    ,                    ,                    \n"
            . "0,                    ,                    ,                    ,                    \n"
            . "0,                    ,                    ,                    ,                    \n"
            . "node,4                                                                               \n"
            . "2,1                   ,0                   ,                    ,                    \n"
            . "2,2                   ,2                   ,                    ,                    \n"
            . "2,3                   ,4                   ,                    ,                    \n"
            . "2,4                   ,6                   ,                    ,                    \n"
            . "node,4                                                                               \n"
            . "2,6                   ,10                  ,                    ,                    \n"
            . "2,7                   ,12                  ,                    ,                    \n"
            . "2,8                   ,14                  ,                    ,                    \n"
            . "2,9                   ,16                  ,                    ,                    \n"
            . "node,4                                                                               \n"
            . "2,11                  ,21                  ,                    ,                    \n"
            . "2,12                  ,24                  ,                    ,                    \n"
            . "2,13                  ,27                  ,                    ,                    \n"
            . "2,14                  ,30                  ,                    ,                    \n"
            . "node,4                                                                               \n"
            . "2,16                  ,36                  ,                    ,                    \n"
            . "2,17                  ,39                  ,                    ,                    \n"
            . "2,18                  ,42                  ,                    ,                    \n"
            . "2,19                  ,45                  ,                    ,                    \n"
            . "node,4                                                                               \n"
            . "2,21                  ,51                  ,                    ,                    \n"
            . "2,22                  ,54                  ,                    ,                    \n"
            . "2,23                  ,57                  ,                    ,                    \n"
            . "0,                    ,                    ,                    ,                    \n");

        $this->btree = new BTree('index', 'data');

        foreach (range(1, 23) as $row) {
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
    }

    /**
     * @covers            \ZataBase\Storage\BTree::find
     * @covers            \ZataBase\Storage\BTree::locate
     * @uses              \ZataBase\Storage\BTree
     */
    public function testFind()
    {
        foreach (range(1, 23) as $row) {
            $this->assertEquals([$row], $this->btree->find($row));
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
              "node,1                                                                               \n"
            . "2,20                  ,48                  ,6                   ,7                   \n"
            . "0,                    ,                    ,                    ,                    \n"
            . "0,                    ,                    ,                    ,                    \n"
            . "0,                    ,                    ,                    ,                    \n",
            UnitUtils::callMethod($this->btree, 'getParent', $this->btree->find(23))->toString());
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
        $this->btree->getData()->appendcsv([24]);
        $this->btree->insertIndex([24, 60]);
        $this->assertEquals([24], $this->btree->find(24));
    }

}
