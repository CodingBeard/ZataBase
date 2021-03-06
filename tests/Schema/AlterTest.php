<?php

/*
 * Alter Test
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

class AlterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \ZataBase\Db
     */
    protected $db;

    protected function setUp()
    {
        $this->db = new Db(new ArrayToObject([
            "databaseDir" => __DIR__ . "/../database",
            "tablesDir" => "tables/"
        ]));
        $this->db->schema->deleteTable('Alter');
        $this->db->schema->createTable(new Table('Alter', [
            new Column('int', Column::INT_TYPE),
            new Column('date', Column::DATE_TYPE),
            new Column('string', Column::STRING_TYPE),
        ]));

        $this->db->insert('Alter')->values([
            [1, '2015-01-01', 'foo'],
            [2, '2015-01-02', 'bar'],
            [3, '2015-01-03', 'fuz'],
            [4, '2015-01-04', 'baz'],
        ]);
    }

    protected function tearDown()
    {
        UnitUtils::deleteDir(__DIR__ . "/../database");
    }

    /**
     * @covers            \ZataBase\Schema\Alter::__construct
     * @uses              \ZataBase\Schema\Alter
     */
    public function testConstruct()
    {
        $alter = $this->db->alterTable('Alter');

        $this->assertInstanceOf('\ZataBase\Schema\Alter', $alter);

        $this->assertInstanceOf('\ZataBase\Table', $this->readAttribute($alter, 'table'));
    }

    /**
     * @covers            \ZataBase\Schema\Alter::addColumn
     * @uses              \ZataBase\Schema\Alter
     */
    public function testAddColumn()
    {
        $this->db->schema->alterTable('Alter')->addColumn(new Column('NewColumn', Column::INT_TYPE));

        $this->assertEquals([
            'int' => new Column('int', Column::INT_TYPE, [], 0),
            'date' => new Column('date', Column::DATE_TYPE, [], 1),
            'string' => new Column('string', Column::STRING_TYPE, [], 2),
            'NewColumn' => new Column('NewColumn', Column::INT_TYPE, [], 3)
        ], $this->db->schema->getTable('Alter')->getColumns());


        $this->assertEquals([
            [1, '2015-01-01', 'foo', ''],
            [2, '2015-01-02', 'bar', ''],
            [3, '2015-01-03', 'fuz', ''],
            [4, '2015-01-04', 'baz', ''],
        ], $this->db->select('Alter')->done()->toArray());
    }

    /**
     * @covers            \ZataBase\Schema\Alter::addColumn
     * @uses              \ZataBase\Schema\Alter
     */
    public function testAddColumnAfter()
    {
        $this->db->schema->alterTable('Alter')->addColumn(new Column('NewColumn', Column::INT_TYPE), 'date');

        $this->assertEquals([
            'int' => new Column('int', Column::INT_TYPE, [], 0),
            'date' => new Column('date', Column::DATE_TYPE, [], 1),
            'NewColumn' => new Column('NewColumn', Column::INT_TYPE, [], 2),
            'string' => new Column('string', Column::STRING_TYPE, [], 3)
        ], $this->db->schema->getTable('Alter')->getColumns());

        $this->assertEquals([
            [1, '2015-01-01', '', 'foo'],
            [2, '2015-01-02', '', 'bar'],
            [3, '2015-01-03', '', 'fuz'],
            [4, '2015-01-04', '', 'baz'],
        ], $this->db->select('Alter')->done()->toArray());
    }

    /**
     * @covers            \ZataBase\Schema\Alter::removeColumn
     * @uses              \ZataBase\Schema\Alter
     */
    public function testRemoveColumn()
    {
        $this->db->schema->alterTable('Alter')->removeColumn('int');

        $this->assertEquals([
            'date' => new Column('date', Column::DATE_TYPE, [], 0),
            'string' => new Column('string', Column::STRING_TYPE, [], 1)
        ], $this->db->schema->getTable('Alter')->getColumns());

        $this->assertEquals([
            ['2015-01-01', 'foo'],
            ['2015-01-02', 'bar'],
            ['2015-01-03', 'fuz'],
            ['2015-01-04', 'baz'],
        ], $this->db->select('Alter')->done()->toArray());
    }

    /**
     * @covers            \ZataBase\Schema\Alter::changeColumn
     * @uses              \ZataBase\Schema\Alter
     */
    public function testChangeColumn()
    {
        $this->db->schema->alterTable('Alter')->changeColumn('int', new Column('Changed', Column::INT_TYPE));

        $this->assertEquals([
            'Changed' => new Column('Changed', Column::INT_TYPE, [], 0),
            'date' => new Column('date', Column::DATE_TYPE, [], 1),
            'string' => new Column('string', Column::STRING_TYPE, [], 2)
        ], $this->db->schema->getTable('Alter')->getColumns());

        $this->assertEquals([
            [1, '2015-01-01', 'foo'],
            [2, '2015-01-02', 'bar'],
            [3, '2015-01-03', 'fuz'],
            [4, '2015-01-04', 'baz'],
        ], $this->db->select('Alter')->done()->toArray());
    }

}
