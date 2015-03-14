<?php

/*
 * Select Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Db;
use ZataBase\Helper\ArrayToObject;
use ZataBase\Table;
use ZataBase\Table\Column;

class SelectTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \ZataBase\Db
     */
    public $db;

    public function setUp()
    {
        $this->db = new Db(new ArrayToObject([
            "databaseDir" => __DIR__ . "/../database",
            "tablesDir" => "tables/"
        ]));
        $this->db->schema->deleteTable('Select');
        $this->db->schema->createTable(new Table('Select', [
            new Column('int', Column::INT_TYPE),
            new Column('date', Column::DATE_TYPE),
            new Column('string', Column::STRING_TYPE),
        ]));

        $this->db->insert('Select')->values([
            [1, '2015-01-01', 'foo'],
            [2, '2015-01-02', 'bar'],
            [3, '2015-01-03', 'fuz'],
            [4, '2015-01-04', 'baz'],
        ]);
    }

    /**
     * @covers            \ZataBase\Execute\Select::done
     * @uses              \ZataBase\Execute\Select
     */
    public function testDone()
    {
        $this->assertInstanceOf('\ZataBase\Execute\Results', $this->db->select('Select')->done());

        $this->assertEquals($this->db->schema->getTable('Select')->selectRows()->toArray(), $this->db->select('Select')->done()->toArray());

        $this->assertEquals(false, $this->db->select('Select')->where('date')->equals('1')->done());
    }

    /**
     * @covers            \ZataBase\Execute\Select::max
     * @uses              \ZataBase\Execute\Select
     */
    public function testMax()
    {
        $this->assertEquals(4, $this->db->select('Select')->max('int'));
        $this->assertEquals('2015-01-04', $this->db->select('Select')->max('date'));
    }

    /**
     * @covers            \ZataBase\Execute\Select::min
     * @uses              \ZataBase\Execute\Select
     */
    public function testMin()
    {
        $this->assertEquals(1, $this->db->select('Select')->min('int'));
        $this->assertEquals('2015-01-01', $this->db->select('Select')->min('date'));
    }



}
