<?php

/*
 * Within Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Execute\Condition\Within;
use ZataBase\Table\Column;

class WithinTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers            \ZataBase\Execute\Condition\Within::__construct
     * @uses              \ZataBase\Execute\Condition\Within
     */
    public function testConstruct()
    {
        $Within = new Within(false, new Column('testConstruct', Column::INT_TYPE, [], 0), [0, 1, 2]);
        $this->assertInstanceOf('\ZataBase\Execute\Condition\Within', $Within);
    }

    /**
     * @covers            \ZataBase\Execute\Condition\Within::matches
     * @uses              \ZataBase\Execute\Condition\Within
     */
    public function testMatches()
    {
        $Within = new Within(false, new Column('test', Column::DATE_TYPE, [], 0), [0, 1, 2]);

        $this->assertTrue($Within->matches([2]));
        $this->assertFalse($Within->matches([4]));
    }

    /**
     * @covers            \ZataBase\Execute\Condition\Within::matches
     * @uses              \ZataBase\Execute\Condition\Within
     */
    public function testMatchesNot()
    {
        $Within = new Within(true, new Column('test', Column::DATE_TYPE, [], 0), [0, 1, 2]);

        $this->assertFalse($Within->matches([1]));
        $this->assertTrue($Within->matches([4]));
    }

}
