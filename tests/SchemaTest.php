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

class SchemaTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \ZataBase\Db
     */
    protected $db;

    protected function setUp()
    {
        $this->db = new Db(new ArrayToObject([
            "databaseDir" => __DIR__ . "/database",
            "tablesDir" => "tables/"
        ]));
    }

    protected function tearDown()
    {
        unlink(__DIR__ . "/database/tables/_schema");
        unlink(__DIR__ . "/database/tables/_increments");
        if (is_file(__DIR__ . "/database/tables/Test")) {
            unlink(__DIR__ . "/database/tables/Test");
        }
    }

    /**
     * @covers            \ZataBase\Schema::__construct
     * @uses              \ZataBase\Schema
     */
    public function testConstruct()
    {
        $this->assertInstanceOf('\ZataBase\Schema', $this->db->schema);
        $handlers = $this->db->schema->getHandlers();

        $this->assertInstanceOf('\ZataBase\Helper\FileHandler', $handlers['schema']);
        $this->assertInstanceOf('\ZataBase\Helper\FileHandler', $handlers['increments']);
    }

    /**
     * @covers            \ZataBase\Schema::refresh
     * @uses              \ZataBase\Schema
     */
    public function testRefresh()
    {
        $this->db->schema->refresh();
        $handlers = $this->db->schema->getHandlers();

        $this->assertInstanceOf('\ZataBase\Helper\FileHandler', $handlers['schema']);
        $this->assertInstanceOf('\ZataBase\Helper\FileHandler', $handlers['increments']);
    }

    /**
     * @covers            \ZataBase\Schema::createTable
     * @uses              \ZataBase\Schema
     */
    public function testCreateTable()
    {
        $this->db->schema->getHandlers()['schema']->ftruncate(0);
        $table = new Table('Test', []);
        $this->db->schema->createTable($table);

        $this->assertEquals($table->__toString() . PHP_EOL, $this->db->schema->getHandlers()['schema']->current());
    }

    /**
     * @covers            \ZataBase\Schema::getTable
     * @uses              \ZataBase\Schema
     */
    public function testGetTable()
    {
        $this->db->schema->getHandlers()['schema']->ftruncate(0);
        $table = new Table('Test', []);
        $table->setDI($this->db->getDI());
        $this->db->schema->createTable($table);

        $this->assertEquals($table, $this->db->schema->getTable('Test'));
    }

    /**
     * @covers            \ZataBase\Schema::createTable
     * @uses              \ZataBase\Schema
     */
    public function testDeleteTable()
    {
        $this->db->schema->getHandlers()['schema']->ftruncate(0);
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
        $this->db->schema->getHandlers()['schema']->ftruncate(0);
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
        $this->db->schema->getHandlers()['schema']->ftruncate(0);
        $table = new Table('Test', [new Table\Column('int', Table\Column::INT_TYPE)]);
        $this->db->schema->createTable($table);
        $table = $this->db->schema->getTable('Test');
        $table->addColumn(new Table\Column('New', Table\Column::INT_TYPE));

        $this->db->schema->saveTable($table);

        $this->assertEquals($table, $this->db->schema->getTable('Test'));
    }

    /**
     * @covers            \ZataBase\Schema::setIncrement
     * @uses              \ZataBase\Schema
     */
    public function testSetIncrement()
    {
        $this->db->schema->setIncrement('Test', 5);

        $this->assertEquals(['Test' => 5], $this->db->schema->getIncrements());
    }

    /**
     * @covers            \ZataBase\Schema::getIncrement
     * @uses              \ZataBase\Schema
     */
    public function testGetIncrement()
    {
        $this->db->schema->getHandlers()['increments']->ftruncate(0);
        $this->db->schema->setIncrements(false);
        $this->db->schema->getHandlers()['increments']->appendcsv(['Test', 5]);

        $this->assertEquals(5, $this->db->schema->getIncrement('Test'));
        $this->assertEquals(5, $this->db->schema->getIncrement('Test'));
        $this->assertEquals(1, $this->db->schema->getIncrement('Undefined'));
    }

    /**
     * @covers            \ZataBase\Schema::shutdown
     * @uses              \ZataBase\Schema
     */
    public function testShutdown()
    {
        $this->db->schema->getHandlers()['increments']->ftruncate(0);
        $this->db->schema->setIncrements(false);
        $this->db->schema->setIncrement('Test', 5);

        $this->db->schema->shutdown();
        $this->setUp();

        $this->assertEquals(5, $this->db->schema->getIncrement('Test'));
    }

}
