<?php

/*
 * LessThan Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Execute\Condition\LessThan;
use ZataBase\Table\Column;

class LessThanTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers            \ZataBase\Execute\Condition\LessThan::__construct
     * @uses              \ZataBase\Execute\Condition\LessThan
     */
    public function testConstruct()
    {
        $LessThan = new LessThan(false, new Column('testConstruct', Column::INT_TYPE, [], 0), 2, true);
        $this->assertInstanceOf('\ZataBase\Execute\Condition\LessThan', $LessThan);
    }

    /**
     * @covers            \ZataBase\Execute\Condition\LessThan::matches
     * @uses              \ZataBase\Execute\Condition\LessThan
     */
    public function testMatchesDateType()
    {
        $LessThan = new LessThan(false, new Column('test', Column::DATE_TYPE, [], 0), '2015-01-10', false);

        $this->assertTrue($LessThan->matches(['2015-01-05']));
        $this->assertFalse($LessThan->matches(['2015-01-20']));
    }

    /**
     * @covers            \ZataBase\Execute\Condition\LessThan::matches
     * @uses              \ZataBase\Execute\Condition\LessThan
     */
    public function testMatchesDateTypeEquals()
    {
        $LessThan = new LessThan(false, new Column('test', Column::DATE_TYPE, [], 0), '2015-01-10', true);

        $this->assertTrue($LessThan->matches(['2015-01-01']));
        $this->assertTrue($LessThan->matches(['2015-01-10']));
        $this->assertFalse($LessThan->matches(['2015-01-20']));
    }

    /**
     * @covers            \ZataBase\Execute\Condition\LessThan::matches
     * @uses              \ZataBase\Execute\Condition\LessThan
     */
    public function testMatchesIntType()
    {
        $LessThan = new LessThan(false, new Column('test', Column::INT_TYPE, [], 0), 10, false);

        $this->assertTrue($LessThan->matches([5]));
        $this->assertFalse($LessThan->matches([20]));
    }

    /**
     * @covers            \ZataBase\Execute\Condition\LessThan::matches
     * @uses              \ZataBase\Execute\Condition\LessThan
     */
    public function testMatchesIntTypeEquals()
    {
        $LessThan = new LessThan(false, new Column('test', Column::INT_TYPE, [], 0), 10, true);

        $this->assertTrue($LessThan->matches([5]));
        $this->assertTrue($LessThan->matches([10]));
        $this->assertFalse($LessThan->matches([20]));
    }

    /**
     * @covers            \ZataBase\Execute\Condition\LessThan::matches
     * @uses              \ZataBase\Execute\Condition\LessThan
     */
    public function testMatchesNot()
    {
        $LessThan = new LessThan(true, new Column('test', Column::INT_TYPE, [], 0), 10, false);

        $this->assertFalse($LessThan->matches([5]));
        $this->assertTrue($LessThan->matches([20]));
    }

}
