<?php

/*
 * Delete Test
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

class DeleteTest extends PHPUnit_Framework_TestCase
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
        $this->db->schema->deleteTable('Delete');
        $this->db->schema->createTable(new Table('Delete', [
            new Column('int', Column::INT_TYPE),
            new Column('date', Column::DATE_TYPE),
            new Column('string', Column::STRING_TYPE),
        ]));

        $this->db->insert('Delete')->values([
            [1, '2015-01-01', 'foo'],
            [2, '2015-01-02', 'bar'],
            [3, '2015-01-03', 'fuz'],
            [4, '2015-01-04', 'baz'],
        ]);
    }

    /**
     * @covers            \ZataBase\Execute\Delete::done
     * @uses              \ZataBase\Execute\Delete
     */
    public function testDoneConditional()
    {
        $this->db->delete('Delete')->where('int')->equals(3)->done();
        $this->assertEquals([
            [1, '2015-01-01', 'foo'],
            [2, '2015-01-02', 'bar'],
            [4, '2015-01-04', 'baz'],
        ], $this->db->select('Delete')->done()->toArray());
    }

    /**
     * @covers            \ZataBase\Execute\Delete::done
     * @uses              \ZataBase\Execute\Delete
     */
    public function testDone()
    {
        $this->db->delete('Delete')->done();
        $this->assertEquals(false, $this->db->select('Delete')->done());
    }



}
