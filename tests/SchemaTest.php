<?php

/*
 * Schema Test
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
use ZataBase\Tests\UnitUtils;

class SchemaTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \ZataBase\Db
     */
    protected $db;

    protected function setUp()
    {
        $this->db = new Db(new ArrayToObject([
            "databaseDir" => __DIR__ . "/database"
        ]));
    }

    protected function tearDown()
    {
        UnitUtils::deleteDir(__DIR__ . "/database");
    }

    /**
     * @covers            \ZataBase\Schema::__construct
     * @uses              \ZataBase\Schema
     */
    public function testConstruct()
    {
        $this->assertInstanceOf('\ZataBase\Schema', $this->db->schema);
    }

    /**
     * @covers            \ZataBase\Schema::refresh
     * @uses              \ZataBase\Schema
     */
    public function testRefresh()
    {
        $this->db->schema->refresh();
    }

    /**
     * @covers            \ZataBase\Schema::createTable
     * @uses              \ZataBase\Schema
     */
    public function testCreateTable()
    {
        $table = new Table('Test', []);
        $this->db->schema->createTable($table);

        $this->assertTrue($this->db->storage->isDir('Test'));
        $this->assertTrue($this->db->storage->isFile('Test/columns'));
        $this->assertTrue($this->db->storage->isFile('Test/data'));
        $this->assertTrue($this->db->storage->isFile('Test/increment'));
        $this->assertTrue($this->db->storage->isFile('Test/relations'));
        $this->assertTrue($this->db->storage->isFile('Test/.zatabasetable'));

        $this->assertEquals(['Test' => $table], $this->db->schema->getTables());
    }

    /**
     * @covers            \ZataBase\Schema::getTable
     * @uses              \ZataBase\Schema
     */
    public function testGetTable()
    {
        $table = new Table('Test', []);
        $this->db->schema->createTable($table);

        $this->assertEquals($table, $this->db->schema->getTable('Test'));
    }

    /**
     * @covers            \ZataBase\Schema::createTable
     * @uses              \ZataBase\Schema
     */
    public function testDeleteTable()
    {
        $table = new Table('Test', []);
        $this->db->schema->createTable($table);
        $this->db->schema->deleteTable('Test');

        $this->assertFalse($this->db->schema->getTable('Test'));
    }

    /**
     * @covers            \ZataBase\Schema::alterTable
     * @uses              \ZataBase\Schema
     */
    public function testAlterTable()
    {
        $this->db->schema->createTable(new Table('Test', []));
        $alter = $this->db->schema->alterTable('Test');

        $this->assertInstanceOf('\ZataBase\Schema\Alter', $alter);
    }

    /**
     * @covers            \ZataBase\Schema::saveTable
     * @uses              \ZataBase\Schema
     */
    public function testSaveTable()
    {
        $table = new Table('Test', [new Table\Column('int', Table\Column::INT_TYPE)]);
        $this->db->schema->createTable($table);
        $table = $this->db->schema->getTable('Test');
        $table->addColumn(new Table\Column('New', Table\Column::INT_TYPE));

        $this->db->schema->saveTable($table);

        $this->assertEquals($table, $this->db->schema->getTable('Test'));
    }

    /**
     * @covers            \ZataBase\Schema::save
     * @uses              \ZataBase\Schema
     */
    public function testSave()
    {
        $table = new Table('Test', []);
        $this->db->schema->createTable($table);
        $this->db->schema->getTables()['Test']->addColumn(new Table\Column('shutdown', Table\Column::INT_TYPE));

        $this->db->schema->save();
        $this->setUp();

        $this->assertEquals(['shutdown' => new Table\Column('shutdown', Table\Column::INT_TYPE)], $this->db->schema->getTable('Test')->getColumns());
    }

}
