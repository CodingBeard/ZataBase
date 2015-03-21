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
use ZataBase\Tests\UnitUtils;

class InsertTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \ZataBase\Db
     */
    public $db;

    public function setUp()
    {
        $this->db = new Db(new ArrayToObject([
            "databaseDir" => __DIR__ . "/../database"
        ]));

        $this->db->schema->createTable(new Table('Insert', [
            new Column('id', Column::INT_TYPE, [Column::INCREMENT_FLAG]),
            new Column('one', Column::INT_TYPE),
            new Column('two', Column::INT_TYPE),
            new Column('three', Column::INT_TYPE),
        ]));
    }

    protected function tearDown()
    {
        UnitUtils::deleteDir(__DIR__ . "/../database");
    }

    /**
     * @covers            \ZataBase\Execute\Insert::columns
     * @uses              \ZataBase\Execute\Insert
     */
    public function testColumns()
    {
        $insert = $this->db->insert('Insert')->columns(['one', 'three']);

        $this->assertEquals([1, 3], $insert->getColumns());
    }

    /**
     * @covers            \ZataBase\Execute\Insert::fillNulls
     * @uses              \ZataBase\Execute\Insert
     */
    public function testFillNulls()
    {
        $insert = $this->db->insert('Insert')->columns(['one', 'three']);

        $this->assertEquals([null, 1, null, 3], UnitUtils::callMethod($insert, 'fillNulls', [[1, 3]]));
    }

    /**
     * @covers            \ZataBase\Execute\Insert::values
     * @uses              \ZataBase\Execute\Insert
     */
    public function testValuesLiteral()
    {
        $this->db->insert('Insert')->values([null, 1, 2, 3]);

        $this->assertEquals([[1, 1, 2, 3]], $this->db->schema->getTable('Insert')->selectRows()->toArray());
    }

    /**
     * @covers            \ZataBase\Execute\Insert::values
     * @uses              \ZataBase\Execute\Insert
     */
    public function testValuesColumns()
    {
        $this->db->insert('Insert')->columns(['one', 'three'])->values([1, 3]);

        $this->assertEquals([[1, 1, null, 3]], $this->db->schema->getTable('Insert')->selectRows()->toArray());
    }

    /**
     * @covers            \ZataBase\Execute\Insert::values
     * @uses              \ZataBase\Execute\Insert
     */
    public function testValuesLiteralMultiple()
    {
        $this->db->insert('Insert')->values([
            [null, 1, 2, 3],
            [null, 4, 5, 6],
        ]);

        $this->assertEquals([
            [1, 1, 2, 3],
            [2, 4, 5, 6],
        ], $this->db->schema->getTable('Insert')->selectRows()->toArray());
    }

    /**
     * @covers            \ZataBase\Execute\Insert::values
     * @uses              \ZataBase\Execute\Insert
     */
    public function testValuesColumnsMultiple()
    {
        $this->db->insert('Insert')->columns(['one', 'three'])->values([
            [1, 3],
            [4, 6]
        ]);

        $this->assertEquals([
            [1, 1, null, 3],
            [2, 4, null, 6]
        ], $this->db->schema->getTable('Insert')->selectRows()->toArray());
    }

}
