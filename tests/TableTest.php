<?php

/*
 * Table Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Db;
use ZataBase\Execute\Condition\Equals;
use ZataBase\Helper\ArrayToObject;
use ZataBase\Table;
use ZataBase\Table\Column;
use ZataBase\Tests\UnitUtils;

class TableTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \ZataBase\Db
     */
    protected $db;

    /**
     * Will test table classes as a property of the database so it can access the DependencyInjector
     */
    protected function setUp()
    {
        $this->db = new Db(new ArrayToObject([
            "databaseDir" => __DIR__ . "/database",
            "tablesDir" => "tables/"
        ]));
        $this->db->testTable = new Table('Test', []);
    }

    protected function tearDown()
    {
        UnitUtils::deleteDir(__DIR__ . "/database");
    }

    /**
     * @covers            \ZataBase\Table::__construct
     * @uses              \ZataBase\Table
     * @expectedException Exception
     * @expectedExceptionMessage Column must be an instance of ZataBase\Table\Column.
     */
    public function testConstructBadColumns()
    {
        $this->db->testConstructBadColumns = new Table('Users', [
            'a', 'b', 'c'
        ]);
    }

    /**
     * @covers            \ZataBase\Table::__construct
     * @uses              \ZataBase\Table
     */
    public function testConstruct()
    {
        $this->db->testConstruct = new Table('Users', []);

        $this->assertInstanceOf('\ZataBase\Table', $this->db->testConstruct);
    }

    /**
     * @covers            \ZataBase\Table::__construct
     * @uses              \ZataBase\Table
     */
    public function testFileHandle()
    {
        $this->db->testConstruct = new Table('Users', []);

        $this->assertInstanceOf('\ZataBase\Helper\FileHandler', $this->db->testConstruct->file);
    }

    /**
     * @covers            \ZataBase\Table::addColumn
     * @uses              \ZataBase\Table
     */
    public function testAddColumn()
    {
        $this->db->testTable->addColumn(new Column('testAddColumnObject', Column::INT_TYPE));

        $this->assertInstanceOf('\ZataBase\Table\Column', $this->db->testTable->hasColumn('testAddColumnObject'));
    }

    /**
     * @covers            \ZataBase\Table::addColumn
     * @uses              \ZataBase\Table
     */
    public function testAddColumnIncrementFlag()
    {
        $this->db->testTable->addColumn(new Column('testAddColumnIncrementFlag', Column::INT_TYPE, [Column::INCREMENT_FLAG]));

        $this->assertInstanceOf('\ZataBase\Table\Column', $this->db->testTable->hasColumn('testAddColumnIncrementFlag'));
    }

    /**
     * @covers            \ZataBase\Table::addColumn
     * @uses              \ZataBase\Table
     * @expectedException Exception
     * @expectedExceptionMessage A table may not have two columns with the same name.
     */
    public function testAddColumnDuplicateException()
    {
        $this->db->testTable->addColumn(new Column('testAddColumnDuplicateException', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('testAddColumnDuplicateException', Column::INT_TYPE));
    }

    /**
     * @covers            \ZataBase\Table::addColumn
     * @uses              \ZataBase\Table
     * @expectedException Exception
     * @expectedExceptionMessage A table may only have one auto-incrementing value.
     */
    public function testAddColumnIncrementFlagException()
    {
        $this->db->testTable->addColumn(new Column('testAddColumnIncrementFlagException', Column::INT_TYPE, [Column::INCREMENT_FLAG]));
        $this->db->testTable->addColumn(new Column('testAddColumnIncrementFlagException2', Column::INT_TYPE, [Column::INCREMENT_FLAG]));
    }

    /**
     * @covers            \ZataBase\Table::hasColumn
     * @uses              \ZataBase\Table
     */
    public function testHasColumn()
    {
        $this->db->testTable->addColumn(new Column('testHasColumn', Column::INT_TYPE));

        $this->assertInstanceOf('\ZataBase\Table\Column', $this->db->testTable->hasColumn('testHasColumn'));

        $this->assertFalse($this->db->testTable->hasColumn('testHasColumnFalse'));
    }

    /**
     * @covers            \ZataBase\Table::columnKey
     * @uses              \ZataBase\Table
     */
    public function testColumnKey()
    {
        $this->db->testTable->addColumn(new Column('testColumnKey', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('testColumnKey1', Column::INT_TYPE));

        $this->assertEquals(0, $this->db->testTable->columnKey('testColumnKey'));
        $this->assertEquals(1, $this->db->testTable->columnKey('testColumnKey1'));
    }

    /**
     * @covers            \ZataBase\Table::insertRow
     * @uses              \ZataBase\Table
     * @expectedException Exception
     * @expectedExceptionMessage Row should contain the same number of values as columns in the table: one, two, three
     */
    public function testInsertRowException()
    {
        $this->db->testTable->addColumn(new Column('one', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('two', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('three', Column::INT_TYPE));

        $this->db->testTable->insertRow([1, 2]);
    }

    /**
     * @covers            \ZataBase\Table::insertRow
     * @uses              \ZataBase\Table
     */
    public function testInsertRow()
    {
        $this->db->testTable->file->ftruncate(0);
        $this->db->testTable->addColumn(new Column('one', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('two', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('three', Column::INT_TYPE));
        $this->db->testTable->insertRow([1, 2, 3]);
        $this->db->testTable->file->rewind();

        $this->assertEquals([1, 2, 3], $this->db->testTable->file->getcsv(0));
    }

    /**
     * @covers            \ZataBase\Table::insertRow
     * @uses              \ZataBase\Table
     */
    public function testInsertRowIncrement()
    {
        $this->db->testTable->file->ftruncate(0);
        $this->db->testTable->addColumn(new Column('one', Column::INT_TYPE, [Column::INCREMENT_FLAG]));
        $this->db->testTable->addColumn(new Column('two', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('three', Column::INT_TYPE));
        $this->db->testTable->insertRow([null, 2, 3]);
        $this->db->testTable->insertRow([null, 3, 4]);
        $this->db->testTable->file->rewind();

        $this->assertEquals([1, 2, 3], $this->db->testTable->file->getcsv(0));
        $this->db->testTable->file->next();
        $this->assertEquals([2, 3, 4], $this->db->testTable->file->getcsv(strlen('1,2,3' . PHP_EOL)));
    }

    /**
     * @covers            \ZataBase\Table::insertRow
     * @uses              \ZataBase\Table
     * @expectedException Exception
     * @expectedExceptionMessage Row should contain the same number of values as columns in the table: one, two, three
     */
    public function testInsertRowsException()
    {
        $this->db->testTable->addColumn(new Column('one', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('two', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('three', Column::INT_TYPE));

        $this->db->testTable->insertRow([
            [1, 2, 3],
            [1, 2],
        ]);
    }

    /**
     * @covers            \ZataBase\Table::insertRows
     * @uses              \ZataBase\Table
     */
    public function testInsertRows()
    {
        $this->db->testTable->file->ftruncate(0);
        $this->db->testTable->addColumn(new Column('one', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('two', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('three', Column::INT_TYPE));
        $this->db->testTable->insertRows([
            [1, 2, 3],
            [4, 5, 6],
        ]);
        $this->db->testTable->file->rewind();

        $this->assertEquals([1, 2, 3], $this->db->testTable->file->fgetcsv());
        $this->db->testTable->file->next();
        $this->assertEquals([4, 5, 6], $this->db->testTable->file->fgetcsv());
    }

    /**
     * @covers            \ZataBase\Table::insertRows
     * @uses              \ZataBase\Table
     */
    public function testInsertRowsIncrement()
    {
        $this->db->testTable->file->ftruncate(0);
        $this->db->testTable->addColumn(new Column('one', Column::INT_TYPE, [Column::INCREMENT_FLAG]));
        $this->db->testTable->addColumn(new Column('two', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('three', Column::INT_TYPE));
        $this->db->testTable->insertRows([
            [null, 2, 3],
            [null, 3, 4],
        ]);
        $this->db->testTable->file->rewind();

        $this->assertEquals([1, 2, 3], $this->db->testTable->file->fgetcsv());
        $this->db->testTable->file->next();
        $this->assertEquals([2, 3, 4], $this->db->testTable->file->fgetcsv());
    }

    /**
     * @covers            \ZataBase\Table::selectRows
     * @uses              \ZataBase\Table
     */
    public function testSelectRows()
    {
        $this->db->testTable->file->ftruncate(0);
        $this->db->testTable->addColumn(new Column('one', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('two', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('three', Column::INT_TYPE));
        $rows = [
            [1, 2, 3],
            [4, 5, 6],
        ];
        $this->db->testTable->insertRows($rows);

        $results = $this->db->testTable->selectRows();

        $this->assertInstanceOf('\Traversable', $results);

        foreach ($results as $key => $row) {
            $this->assertEquals($rows[$key], $row);
        }
    }

    /**
     * @covers            \ZataBase\Table::selectRows
     * @uses              \ZataBase\Table
     */
    public function testSelectRowsConditions()
    {
        $this->db->testTable->file->ftruncate(0);
        $this->db->testTable->addColumn(new Column('one', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('two', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('three', Column::INT_TYPE));
        $rows = [
            [1, 2, 3],
            [2, 1, 3],
            [3, 2, 3],
        ];
        $this->db->testTable->insertRows($rows);

        $conditions = [
            new Equals(false, new Column('two', Column::INT_TYPE, [], 1), 2)
        ];

        $results = $this->db->testTable->selectRows($conditions);

        $this->assertInstanceOf('\Traversable', $results);

        $expected = [
            [1, 2, 3],
            [3, 2, 3],
        ];

        foreach ($results as $key => $row) {
            $this->assertEquals($expected[$key], $row);
        }
    }

    /**
     * @covers            \ZataBase\Table::deleteRows
     * @uses              \ZataBase\Table
     */
    public function testDeleteRows()
    {
        $this->db->testTable->file->ftruncate(0);
        $this->db->testTable->addColumn(new Column('one', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('two', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('three', Column::INT_TYPE));
        $rows = [
            [1, 2, 3],
            [2, 1, 3],
            [3, 2, 3],
        ];
        $this->db->testTable->insertRows($rows);

        $this->db->testTable->deleteRows(strlen('1,2,3' . PHP_EOL));

        $results = $this->db->testTable->selectRows();

        $this->assertInstanceOf('\Traversable', $results);

        $expected = [
            [1, 2, 3],
            [3, 2, 3],
        ];

        foreach ($results as $key => $row) {
            $this->assertEquals($expected[$key], $row);
        }
    }

    /**
     * @covers            \ZataBase\Table::deleteAllRows
     * @uses              \ZataBase\Table
     */
    public function testDeleteAllRows()
    {
        $this->db->testTable->file->ftruncate(0);
        $this->db->testTable->addColumn(new Column('one', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('two', Column::INT_TYPE));
        $this->db->testTable->addColumn(new Column('three', Column::INT_TYPE));
        $this->db->testTable->insertRows([
            [1, 2, 3],
            [2, 1, 3],
            [3, 2, 3],
        ]);

        $this->db->testTable->deleteAllRows();

        $results = $this->db->testTable->selectRows();

        $this->assertInstanceOf('\Traversable', $results);

        $this->assertEquals(0, $results->count());
    }
}
