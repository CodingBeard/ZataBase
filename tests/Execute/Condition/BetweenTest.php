<?php

/*
 * Between Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Execute\Condition\Between;
use ZataBase\Table\Column;

class BetweenTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers            \ZataBase\Execute\Condition\Between::__construct
     * @uses              \ZataBase\Execute\Condition\Between
     */
    public function testConstruct()
    {
        $between = new Between(false, new Column('testConstruct', Column::INT_TYPE, [], 0), 0, 2, true);
        $this->assertInstanceOf('\ZataBase\Execute\Condition\Between', $between);
    }

    /**
     * @covers            \ZataBase\Execute\Condition\Between::matches
     * @uses              \ZataBase\Execute\Condition\Between
     */
    public function testMatchesDateType()
    {
        $between = new Between(false, new Column('test', Column::DATE_TYPE, [], 0), '2015-01-01', '2015-01-10', false);

        $this->assertTrue($between->matches(['2015-01-05']));
        $this->assertFalse($between->matches(['2015-01-10']));
        $this->assertFalse($between->matches(['2015-01-20']));
    }

    /**
     * @covers            \ZataBase\Execute\Condition\Between::matches
     * @uses              \ZataBase\Execute\Condition\Between
     */
    public function testMatchesDateTypeEquals()
    {
        $between = new Between(false, new Column('test', Column::DATE_TYPE, [], 0), '2015-01-01', '2015-01-10', true);

        $this->assertTrue($between->matches(['2015-01-01']));
        $this->assertTrue($between->matches(['2015-01-10']));
        $this->assertFalse($between->matches(['2015-01-20']));
    }

    /**
     * @covers            \ZataBase\Execute\Condition\Between::matches
     * @uses              \ZataBase\Execute\Condition\Between
     */
    public function testMatchesIntType()
    {
        $between = new Between(false, new Column('test', Column::INT_TYPE, [], 0), 0, 10, false);

        $this->assertTrue($between->matches([5]));
        $this->assertFalse($between->matches([10]));
        $this->assertFalse($between->matches([20]));
    }

    /**
     * @covers            \ZataBase\Execute\Condition\Between::matches
     * @uses              \ZataBase\Execute\Condition\Between
     */
    public function testMatchesIntTypeEquals()
    {
        $between = new Between(false, new Column('test', Column::INT_TYPE, [], 0), 0, 10, true);

        $this->assertTrue($between->matches([5]));
        $this->assertTrue($between->matches([10]));
        $this->assertFalse($between->matches([20]));
    }

    /**
     * @covers            \ZataBase\Execute\Condition\Between::matches
     * @uses              \ZataBase\Execute\Condition\Between
     */
    public function testMatchesNot()
    {
        $between = new Between(true, new Column('test', Column::INT_TYPE, [], 0), 0, 10, false);

        $this->assertFalse($between->matches([5]));
        $this->assertTrue($between->matches([10]));
        $this->assertTrue($between->matches([20]));
    }

}
