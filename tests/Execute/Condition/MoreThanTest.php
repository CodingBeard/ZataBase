<?php

/*
 * MoreThan Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Execute\Condition\MoreThan;
use ZataBase\Table\Column;

class MoreThanTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers            \ZataBase\Execute\Condition\MoreThan::__construct
     * @uses              \ZataBase\Execute\Condition\MoreThan
     */
    public function testConstruct()
    {
        $MoreThan = new MoreThan(false, new Column('testConstruct', Column::INT_TYPE, [], 0), 2, true);
        $this->assertInstanceOf('\ZataBase\Execute\Condition\MoreThan', $MoreThan);
    }

    /**
     * @covers            \ZataBase\Execute\Condition\MoreThan::matches
     * @uses              \ZataBase\Execute\Condition\MoreThan
     */
    public function testMatchesDateType()
    {
        $MoreThan = new MoreThan(false, new Column('test', Column::DATE_TYPE, [], 0), '2015-01-10', false);

        $this->assertFalse($MoreThan->matches(['2015-01-05']));
        $this->assertTrue($MoreThan->matches(['2015-01-20']));
    }

    /**
     * @covers            \ZataBase\Execute\Condition\MoreThan::matches
     * @uses              \ZataBase\Execute\Condition\MoreThan
     */
    public function testMatchesDateTypeEquals()
    {
        $MoreThan = new MoreThan(false, new Column('test', Column::DATE_TYPE, [], 0), '2015-01-10', true);

        $this->assertFalse($MoreThan->matches(['2015-01-01']));
        $this->assertTrue($MoreThan->matches(['2015-01-10']));
        $this->assertTrue($MoreThan->matches(['2015-01-20']));
    }

    /**
     * @covers            \ZataBase\Execute\Condition\MoreThan::matches
     * @uses              \ZataBase\Execute\Condition\MoreThan
     */
    public function testMatchesIntType()
    {
        $MoreThan = new MoreThan(false, new Column('test', Column::INT_TYPE, [], 0), 10, false);

        $this->assertFalse($MoreThan->matches([5]));
        $this->assertTrue($MoreThan->matches([20]));
    }

    /**
     * @covers            \ZataBase\Execute\Condition\MoreThan::matches
     * @uses              \ZataBase\Execute\Condition\MoreThan
     */
    public function testMatchesIntTypeEquals()
    {
        $MoreThan = new MoreThan(false, new Column('test', Column::INT_TYPE, [], 0), 10, true);

        $this->assertFalse($MoreThan->matches([5]));
        $this->assertTrue($MoreThan->matches([10]));
        $this->assertTrue($MoreThan->matches([20]));
    }

    /**
     * @covers            \ZataBase\Execute\Condition\MoreThan::matches
     * @uses              \ZataBase\Execute\Condition\MoreThan
     */
    public function testMatchesNot()
    {
        $MoreThan = new MoreThan(true, new Column('test', Column::INT_TYPE, [], 0), 10, false);

        $this->assertTrue($MoreThan->matches([5]));
        $this->assertFalse($MoreThan->matches([20]));
    }

}
