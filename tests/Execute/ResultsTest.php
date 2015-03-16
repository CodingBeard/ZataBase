<?php

/*
 * Results Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Db;
use ZataBase\Execute\Results;
use ZataBase\Helper\ArrayToObject;
use ZataBase\Table;

class ResultsTest extends PHPUnit_Framework_TestCase
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
        $this->db->testTable = new Table('Test', []);
        $this->db->testResults = new Results($this->db->testTable);
    }

    /**
     * @covers            \ZataBase\Execute\Results::__construct
     * @uses              \ZataBase\Execute\Results
     */
    public function testConstruct()
    {
        $this->assertInstanceOf('\ZataBase\Execute\Results', $this->db->testResults);
        $this->assertInstanceOf('\SeekableIterator', $this->db->testResults);
        $this->assertInstanceOf('\ArrayAccess', $this->db->testResults);
        $this->assertInstanceOf('\Traversable', $this->db->testResults);
    }

    /**
     * @covers            \ZataBase\Execute\Results::count
     * @uses              \ZataBase\Execute\Results
     */
    public function testAddRowOffset()
    {
        $this->db->testResults->addRowOffset(30);

        $this->assertEquals([30], $this->db->testResults->getRows());
    }

    /**
     * @covers            \ZataBase\Execute\Results::count
     * @uses              \ZataBase\Execute\Results
     */
    public function testCount()
    {
        $this->assertEquals(0, $this->db->testResults->count());

        $this->db->testResults->addRowOffset(0);
        $this->db->testResults->addRowOffset(30);

        $this->assertEquals(2, $this->db->testResults->count());
    }

    /**
     * @covers            \ZataBase\Execute\Results::count
     * @uses              \ZataBase\Execute\Results
     */
    public function testGetRow()
    {
        $this->db->testTable->file->appendcsv(['one', 'two']);
        $this->db->testTable->file->appendcsv(['three', 'four']);

        $this->db->testResults->addRowOffset(0);
        $this->db->testResults->addRowOffset(strlen('one,two' . PHP_EOL));

        $this->assertEquals(['one', 'two'], $this->db->testResults->getRow(0));

        $this->assertEquals(['three', 'four'], $this->db->testResults->getRow(1));
    }

    /**
     * @covers            \ZataBase\Execute\Results::toArray
     * @uses              \ZataBase\Execute\Results
     */
    public function testToArray()
    {
        $this->db->testTable->file->ftruncate(0);
        $this->db->testTable->file->appendcsv(['one', 'two']);
        $this->db->testTable->file->appendcsv(['three', 'four']);

        $this->db->testResults->addRowOffset(0);
        $this->db->testResults->addRowOffset(strlen('one,two' . PHP_EOL));



        $this->assertEquals([['one', 'two'], ['three', 'four']], $this->db->testResults->toArray());
    }

    /**
     * @covers            \ZataBase\Execute\Results::count
     * @uses              \ZataBase\Execute\Results
     */
    public function testGetOffset()
    {
        $this->db->testResults->addRowOffset(0);
        $this->db->testResults->addRowOffset(strlen(json_encode(['one', 'two']) . PHP_EOL));

        $this->assertEquals(strlen(json_encode(['one', 'two']) . PHP_EOL), $this->db->testResults->getOffset(1));
    }

    /**
     * @covers            \ZataBase\Execute\Results::count
     * @uses              \ZataBase\Execute\Results
     */
    public function testIterability()
    {
        $length = $this->db->testTable->file->appendcsv(['one', 'two']);
        $this->db->testTable->file->appendcsv(['three', 'four']);

        $this->db->testResults->addRowOffset(0);
        $this->db->testResults->addRowOffset($length);

        foreach ($this->db->testResults as $key => $row) {
            $this->assertEquals($row, $this->db->testResults->getRow($key));
        }
    }

    /**
     * @covers            \ZataBase\Execute\Results::count
     * @uses              \ZataBase\Execute\Results
     */
    public function testArrayAccess()
    {
        $length = $this->db->testTable->file->appendcsv(['one', 'two']);
        $this->db->testTable->file->appendcsv(['three', 'four']);

        $this->db->testResults->addRowOffset(0);
        $this->db->testResults->addRowOffset($length);

        $this->assertEquals($this->db->testResults[0], $this->db->testResults->getRow(0));
        $this->assertEquals($this->db->testResults[1], $this->db->testResults->getRow(1));

    }
}
