<?php

/*
 * Update Test
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

class UpdateTest extends PHPUnit_Framework_TestCase
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
        $this->db->schema->deleteTable('Update');
        $this->db->schema->createTable(new Table('Update', [
            new Column('id', Column::INT_TYPE, [Column::INCREMENT_FLAG]),
            new Column('int', Column::INT_TYPE),
            new Column('date', Column::DATE_TYPE),
            new Column('string', Column::STRING_TYPE),
        ]));

        $this->db->insert('Update')->columns(['int', 'date', 'string'])->values([
            [1, '2015-01-01', 'foo'],
            [2, '2015-01-02', 'bar'],
            [3, '2015-01-03', 'fuz'],
            [4, '2015-01-04', 'baz'],
        ]);
    }

    /**
     * @covers            \ZataBase\Execute\Update::setColumns
     * @uses              \ZataBase\Execute\Update
     */
    public function testSetColumns()
    {
        $update = $this->db->update('Update')->setColumns('int');

        $this->assertEquals([1], $this->getObjectAttribute($update, 'columns'));

        $update = $this->db->update('Update')->setColumns(['int', 'string']);

        $this->assertEquals([1, 3], $this->getObjectAttribute($update, 'columns'));
    }

    /**
     * @covers            \ZataBase\Execute\Update::values
     * @uses              \ZataBase\Execute\Update
     */
    public function testValues()
    {
        $update = $this->db->update('Update')->setColumns('int')->values(10);

        $this->assertEquals([10], $this->getObjectAttribute($update, 'values'));

        $update = $this->db->update('Update')->setColumns(['int', 'string'])->values([10, 'thirty']);

        $this->assertEquals([10, 'thirty'], $this->getObjectAttribute($update, 'values'));
    }

    /**
     * @covers            \ZataBase\Execute\Update::done
     * @uses              \ZataBase\Execute\Update
     */
    public function testDoneSingle()
    {
        $this->db->update('Update')->setColumns('int')->values(10)->done();

        $this->assertEquals([
            [1, 10, '2015-01-01', 'foo'],
            [2, 10, '2015-01-02', 'bar'],
            [3, 10, '2015-01-03', 'fuz'],
            [4, 10, '2015-01-04', 'baz'],
        ], $this->db->select('Update')->done()->toArray());
    }

    /**
     * @covers            \ZataBase\Execute\Update::done
     * @uses              \ZataBase\Execute\Update
     */
    public function testDoneMultiple()
    {

        $this->db->update('Update')->setColumns(['int', 'string'])->values([10, 'thirty'])->done();

        $this->assertEquals([
            [1, 10, '2015-01-01', 'thirty'],
            [2, 10, '2015-01-02', 'thirty'],
            [3, 10, '2015-01-03', 'thirty'],
            [4, 10, '2015-01-04', 'thirty'],
        ], $this->db->select('Update')->done()->toArray());
    }


}
