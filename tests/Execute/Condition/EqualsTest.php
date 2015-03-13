<?php

/*
 * Equals Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Execute\Condition\Equals;
use ZataBase\Table\Column;

class EqualsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers            \ZataBase\Execute\Condition\Equals::__construct
     * @uses              \ZataBase\Execute\Condition\Equals
     */
    public function testConstruct()
    {
        $Equals = new Equals(false, new Column('testConstruct', Column::INT_TYPE, [], 0), 2);
        $this->assertInstanceOf('\ZataBase\Execute\Condition\Equals', $Equals);
    }

    /**
     * @covers            \ZataBase\Execute\Condition\Equals::matches
     * @uses              \ZataBase\Execute\Condition\Equals
     */
    public function testMatches()
    {
        $Equals = new Equals(false, new Column('test', Column::DATE_TYPE, [], 0), '2015-01-01');

        $this->assertTrue($Equals->matches(['2015-01-01']));
        $this->assertFalse($Equals->matches(['2015-01-02']));
    }

    /**
     * @covers            \ZataBase\Execute\Condition\Equals::matches
     * @uses              \ZataBase\Execute\Condition\Equals
     */
    public function testMatchesNot()
    {
        $Equals = new Equals(true, new Column('test', Column::DATE_TYPE, [], 0), '2015-01-01');

        $this->assertFalse($Equals->matches(['2015-01-01']));
        $this->assertTrue($Equals->matches(['2015-01-02']));
    }

}
