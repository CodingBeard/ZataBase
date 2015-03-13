<?php

/*
 * Insert Test
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

class InsertTest extends PHPUnit_Framework_TestCase
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
        $this->db->schema->deleteTable('Insert');
        $this->db->schema->createTable(new Table('Insert', [
            new Column('one', Column::INT_TYPE),
            new Column('two', Column::INT_TYPE),
            new Column('three', Column::INT_TYPE),
        ]));
    }

    /**
     * @covers            \ZataBase\Execute\Insert::columns
     * @uses              \ZataBase\Execute\Insert
     */
    public function testColumns()
    {
        $insert = $this->db->insert('Insert')->columns(['one', 'three']);

        $this->assertEquals([0, 2], $insert->getColumns());
    }

    /**
     * @covers            \ZataBase\Execute\Insert::fillNulls
     * @uses              \ZataBase\Execute\Insert
     */
    public function testFillNulls()
    {
        $insert = $this->db->insert('Insert')->columns(['one', 'three']);

        $reflection = new ReflectionClass('\ZataBase\Execute\Insert');
        $fillNulls = $reflection->getMethod('fillNulls');
        $fillNulls->setAccessible(true);

        $this->assertEquals([1, null, 3], $fillNulls->invokeArgs($insert, [[1, 3]]));
    }

}
