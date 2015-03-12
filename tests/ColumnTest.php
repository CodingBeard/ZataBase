<?php

/*
 * Column Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */


use ZataBase\Table\Column;

class ColumnTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers            \ZataBase\Table\Column::__construct
     * @uses              \ZataBase\Table\Column
     */
    public function testConstruct()
    {
        $column = new Column('Name', Column::INT_TYPE);
        $this->assertInstanceOf('\ZataBase\Table\Column', $column);
    }

    /**
     * @covers            \ZataBase\Table\Column::hasFlag
     * @uses              \ZataBase\Table\Column
     */
    public function testHasFlag()
    {
        $column = new Column('Name', Column::INT_TYPE, [Column::INCREMENT_FLAG]);

        $this->assertTrue($column->hasFlag(Column::INCREMENT_FLAG));
    }

    /**
     * @covers            \ZataBase\Table\Column::hasFlag
     * @uses              \ZataBase\Table\Column
     */
    public function testToString()
    {
        $column = new Column('Name', Column::INT_TYPE, [Column::INCREMENT_FLAG]);

        $this->assertEquals(json_encode(['Name', Column::INT_TYPE, [Column::INCREMENT_FLAG]]), $column);
    }
}
