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

        $this->btree = new BTree('index', 'data');

        foreach (range(1, 24) as $row) {
            $this->btree->getData()->appendcsv([$row]);
        }

        $this->btree->getIndex()->appendRaw("node,1\n1,15,33,21,57\nnode,2\n1,5,8,80,111\n1,10,18,111,146\nnode,1\n1,20,48,185,224\nnode,4\n1,1,0\n1,2,2\n1,3,4\n1,4,6\nnode,4\n1,6,10\n1,7,12\n1,8,14\n1,9,16\nnode,4\n1,11,21\n1,12,24\n1,13,27\n1,14,30\nnode,4\n1,16,36\n1,17,39\n1,18,42\n1,19,45\nnode,4\n1,21,51\n1,22,54\n1,23,57\n1,24,60");

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
    }

    /**
     * @covers            \ZataBase\Storage\BTree::find
     * @covers            \ZataBase\Storage\BTree::locate
     * @uses              \ZataBase\Storage\BTree
     */
    public function testFind()
    {
        foreach (range(1, 24) as $row) {
            $this->assertEquals([$row], $this->btree->find($row));
        }
    }

    /**
     * @covers            \ZataBase\Storage\BTree::find
     * @uses              \ZataBase\Storage\BTree
     * @expectedException Exception
     * @expectedExceptionMessage The provided Key: '15' must be unique.
     */
    public function testInsertException()
    {
        $this->btree->insert([15, 48]);
    }

}
