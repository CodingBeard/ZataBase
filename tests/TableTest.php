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

use ZataBase\Table;
use ZataBase\Table\Column;

class TableTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \ZataBase\Storage\Adapter\File
     */
    protected $table;

    protected function setUp()
    {
        $this->table = new Table('Users', [
            new Column('id', Column::INT_TYPE, [Column::INCREMENT_FLAG]),
            new Column('firstName', Column::STRING_TYPE),
            new Column('lastName', Column::STRING_TYPE),
            new Column('DoB', Column::DATE_TYPE),
        ]);
    }

    /**
     * @covers            \ZataBase\Storage\Adapter\File::__construct
     * @uses              \ZataBase\Storage\Adapter\File
     */
    public function testConstruct()
    {
        $file = new File(__DIR__ . '/database');
        $this->assertInstanceOf('\ZataBase\Storage\Adapter\File', $file);
    }
}
