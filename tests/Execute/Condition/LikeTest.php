<?php

/*
 * Like Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Execute\Condition\Like;
use ZataBase\Table\Column;

class LikeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers            \ZataBase\Execute\Condition\Like::__construct
     * @uses              \ZataBase\Execute\Condition\Like
     */
    public function testConstruct()
    {
        $Like = new Like(false, new Column('testConstruct', Column::INT_TYPE, [], 0), 'test');
        $this->assertInstanceOf('\ZataBase\Execute\Condition\Like', $Like);
    }

    /**
     * @covers            \ZataBase\Execute\Condition\Like::matches
     * @uses              \ZataBase\Execute\Condition\Like
     */
    public function testConstructPattern()
    {
        $Like = new Like(false, new Column('test', Column::DATE_TYPE, [], 0), 'test');
        $this->assertEquals('#^test$#is', $Like->getPattern());

        $Like = new Like(false, new Column('test', Column::DATE_TYPE, [], 0), 't%t');
        $this->assertEquals('#^t(.*)t$#is', $Like->getPattern());

        $Like = new Like(false, new Column('test', Column::DATE_TYPE, [], 0), 'te_t');
        $this->assertEquals('#^te(.)t$#is', $Like->getPattern());

        $Like = new Like(false, new Column('test', Column::DATE_TYPE, [], 0), 't\%t');
        $this->assertEquals('#^t%t$#is', $Like->getPattern());

        $Like = new Like(false, new Column('test', Column::DATE_TYPE, [], 0), 't\_t');
        $this->assertEquals('#^t_t$#is', $Like->getPattern());
    }

    /**
     * @covers            \ZataBase\Execute\Condition\Like::matches
     * @uses              \ZataBase\Execute\Condition\Like
     */
    public function testMatches()
    {
        $Like = new Like(false, new Column('test', Column::DATE_TYPE, [], 0), 'test');
        $this->assertTrue($Like->matches(['test']));
        $this->assertFalse($Like->matches(['Foo']));

        $Like = new Like(false, new Column('test', Column::DATE_TYPE, [], 0), 't%t');
        $this->assertTrue($Like->matches(['test']));
        $this->assertFalse($Like->matches(['tesf']));

        $Like = new Like(false, new Column('test', Column::DATE_TYPE, [], 0), 'te_t');
        $this->assertTrue($Like->matches(['test']));
        $this->assertFalse($Like->matches(['tesf']));

        $Like = new Like(false, new Column('test', Column::DATE_TYPE, [], 0), 't\%t');
        $this->assertTrue($Like->matches(['t%t']));
        $this->assertFalse($Like->matches(['test']));

        $Like = new Like(false, new Column('test', Column::DATE_TYPE, [], 0), 't\_t');
        $this->assertTrue($Like->matches(['t_t']));
        $this->assertFalse($Like->matches(['test']));
    }

    /**
     * @covers            \ZataBase\Execute\Condition\Like::matches
     * @uses              \ZataBase\Execute\Condition\Like
     */
    public function testMatchesNot()
    {
        $Like = new Like(true, new Column('test', Column::DATE_TYPE, [], 0), 'test');
        $this->assertFalse($Like->matches(['test']));
        $this->assertTrue($Like->matches(['Foo']));

        $Like = new Like(true, new Column('test', Column::DATE_TYPE, [], 0), 't%t');
        $this->assertFalse($Like->matches(['test']));
        $this->assertTrue($Like->matches(['tesf']));

        $Like = new Like(true, new Column('test', Column::DATE_TYPE, [], 0), 'te_t');
        $this->assertFalse($Like->matches(['test']));
        $this->assertTrue($Like->matches(['tesf']));

        $Like = new Like(true, new Column('test', Column::DATE_TYPE, [], 0), 't\%t');
        $this->assertFalse($Like->matches(['t%t']));
        $this->assertTrue($Like->matches(['test']));

        $Like = new Like(true, new Column('test', Column::DATE_TYPE, [], 0), 't\_t');
        $this->assertFalse($Like->matches(['t_t']));
        $this->assertTrue($Like->matches(['test']));
    }

}
