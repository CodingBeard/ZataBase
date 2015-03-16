<?php

/*
 * Complex Result Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Db;
use ZataBase\Execute\ComplexResults;
use ZataBase\Helper\ArrayToObject;
use ZataBase\Table;
use ZataBase\Table\Column;

class ComplexResultTest extends PHPUnit_Framework_TestCase
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

        $this->db->deleteTable('Users');

        $this->db->createTable(new Table('Users',
            [
                new Column('id', Column::INT_TYPE, [Column::INCREMENT_FLAG]),
                new Column('firstName', Column::STRING_TYPE),
                new Column('lastName', Column::STRING_TYPE),
                new Column('DoB', Column::DATE_TYPE),
            ],
            [
                new Table\Relations\HasOne('Hats', 'user_id', 'id')
            ]
        ));

        $this->db->deleteTable('Hats');

        $this->db->createTable(new Table('Hats',
            [
                new Column('id', Column::INT_TYPE, [Column::INCREMENT_FLAG]),
                new Column('user_id', Column::INT_TYPE),
                new Column('name', Column::STRING_TYPE),
            ],
            [
                new Table\Relations\BelongsTo('Users', 'id', 'user_id')
            ]));


        $this->db->insert('Users')->columns(['firstName', 'lastName', 'DoB'])
            ->values([
                ['John', 'doe', '1994-07-05'],
                ['Jane', 'doe', '1994-07-06'],
                ['Joan', 'doe', '1994-07-07'],
            ]);


        $this->db->insert('Hats')->columns(['user_id', 'name'])
            ->values([
                [1, 'Top Hat'],
                [2, 'Bowler'],
            ]);

        $this->db->testResults = new ComplexResults([
            $this->db->schema->getTable('Users'),
            $this->db->schema->getTable('Hats')
        ]);
    }

    /**
     * @covers            \ZataBase\Execute\ComplexResults::__construct
     * @uses              \ZataBase\Execute\ComplexResults
     */
    public function testConstruct()
    {
        $this->assertInstanceOf('\ZataBase\Execute\ComplexResults', $this->db->testResults);
        $this->assertInstanceOf('\SeekableIterator', $this->db->testResults);
        $this->assertInstanceOf('\ArrayAccess', $this->db->testResults);
        $this->assertInstanceOf('\Traversable', $this->db->testResults);
    }

    /**
     * @covers            \ZataBase\Execute\ComplexResults::count
     * @uses              \ZataBase\Execute\ComplexResults
     */
    public function testAddRowOffset()
    {
        $this->db->testResults->addRowOffset([30, 31]);

        $this->assertEquals([[30, 31]], $this->db->testResults->getRows());
    }

    /**
     * @covers            \ZataBase\Execute\ComplexResults::count
     * @uses              \ZataBase\Execute\ComplexResults
     */
    public function testCount()
    {
        $this->assertEquals(0, $this->db->testResults->count());

        $this->db->testResults->addRowOffset([0, 0]);
        $this->db->testResults->addRowOffset([30, 30]);

        $this->assertEquals(2, $this->db->testResults->count());
    }

    /**
     * @covers            \ZataBase\Execute\ComplexResults::count
     * @uses              \ZataBase\Execute\ComplexResults
     */
    public function testGetRow()
    {
        $this->db->testResults->addRowOffset([0, 0]);
        $this->db->testResults->addRowOffset([
            strlen('1,John,doe,1994-07-05' . PHP_EOL),
            strlen('1,1,"Top Hat"' . PHP_EOL)
        ]);

        $this->assertEquals([1, 'John', 'doe', '1994-07-05', 1, 1, 'Top Hat'], $this->db->testResults->getRow(0));

        $this->assertEquals([2, 'Jane', 'doe', '1994-07-06', 2, 2, 'Bowler'], $this->db->testResults->getRow(1));
    }

    /**
     * @covers            \ZataBase\Execute\ComplexResults::toArray
     * @uses              \ZataBase\Execute\ComplexResults
     */
    public function testToArray()
    {
        $this->db->testResults->addRowOffset([0, 0]);
        $this->db->testResults->addRowOffset([
            strlen('1,John,doe,1994-07-05' . PHP_EOL),
            strlen('1,1,"Top Hat"' . PHP_EOL)
        ]);

        $this->assertEquals([
            [1, 'John', 'doe', '1994-07-05', 1, 1, 'Top Hat'],
            [2, 'Jane', 'doe', '1994-07-06', 2, 2, 'Bowler']
        ], $this->db->testResults->toArray());
    }

    /**
     * @covers            \ZataBase\Execute\ComplexResults::count
     * @uses              \ZataBase\Execute\ComplexResults
     */
    public function testGetOffset()
    {
        $this->db->testResults->addRowOffset([0, 0]);
        $this->db->testResults->addRowOffset([
            strlen('1,John,doe,1994-07-05' . PHP_EOL),
            strlen('1,1,"Top Hat"' . PHP_EOL)
        ]);

        $this->assertEquals([
            strlen('1,John,doe,1994-07-05' . PHP_EOL),
            strlen('1,1,"Top Hat"' . PHP_EOL)
        ], $this->db->testResults->getOffset(1));
    }

    /**
     * @covers            \ZataBase\Execute\ComplexResults::count
     * @uses              \ZataBase\Execute\ComplexResults
     */
    public function testIterability()
    {
        $this->db->testResults->addRowOffset([0, 0]);
        $this->db->testResults->addRowOffset([
            strlen('1,John,doe,1994-07-05' . PHP_EOL),
            strlen('1,1,"Top Hat"' . PHP_EOL)
        ]);

        foreach ($this->db->testResults as $key => $row) {
            $this->assertEquals($row, $this->db->testResults->getRow($key));
        }
    }

    /**
     * @covers            \ZataBase\Execute\ComplexResults::count
     * @uses              \ZataBase\Execute\ComplexResults
     */
    public function testArrayAccess()
    {

        $this->db->testResults->addRowOffset(0);
        $this->db->testResults->addRowOffset([
            strlen('1,John,doe,1994-07-05' . PHP_EOL),
            strlen('1,1,"Top Hat"' . PHP_EOL)
        ]);

        $this->assertEquals($this->db->testResults[0], $this->db->testResults->getRow(0));
        $this->assertEquals($this->db->testResults[1], $this->db->testResults->getRow(1));

    }

    /**
     * @covers            \ZataBase\Execute\ComplexResults::count
     * @uses              \ZataBase\Execute\ComplexResults
     */
    public function testSelectJoin()
    {
        print_r($this->db->select('Users')->join('Hats')->done()->toArray());
    }
}
