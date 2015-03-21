<?php

/*
 * Condition Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Db;
use ZataBase\Execute\Condition;
use ZataBase\Helper\ArrayToObject;
use ZataBase\Table;
use ZataBase\Table\Column;
use ZataBase\Tests\UnitUtils;

class ConditionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \ZataBase\Db
     */
    public $db;

    public function setUp()
    {
        $this->db = new Db(new ArrayToObject([
            "databaseDir" => __DIR__ . "/../database"
        ]));

        $this->db->schema->createTable(new Table('Condition', [
            new Column('one', Column::INT_TYPE),
            new Column('two', Column::INT_TYPE),
            new Column('three', Column::INT_TYPE),
        ]));
        $this->db->condition = new Condition($this->db->getDI(), $this->db->schema->getTable('Condition'));
    }

    protected function tearDown()
    {
        UnitUtils::deleteDir(__DIR__ . "/../database");
    }

    /**
     * @covers            \ZataBase\Execute\QueryType::__construct
     * @uses              \ZataBase\Execute\Condition
     */
    public function testConstruct()
    {
        $this->assertInstanceOf('\ZataBase\Execute\Condition', $this->db->condition);
    }

    /**
     * @covers            \ZataBase\Execute\Condition::where
     * @uses              \ZataBase\Execute\Condition
     * @expectedException Exception
     * @expectedExceptionMessage Cannot select column: 'NotDefined' It does not exist.
     */
    public function testWhereException()
    {
        $this->db->condition->where('NotDefined');
    }

    /**
     * @covers            \ZataBase\Execute\Condition::where
     * @uses              \ZataBase\Execute\Condition
     */
    public function testWhere()
    {
        $this->db->condition->where('one');

        $this->assertEquals($this->readAttribute($this->db->condition, 'table')->hasColumn('one'), $this->readAttribute($this->db->condition, 'currentColumn'));
    }

    /**
     * @covers            \ZataBase\Execute\Condition::not
     * @uses              \ZataBase\Execute\Condition
     */
    public function testNot()
    {
        $this->db->condition->where('one')->not();

        $this->assertEquals($this->readAttribute($this->db->condition, 'table')->hasColumn('one'), $this->readAttribute($this->db->condition, 'currentColumn'));
        $this->assertEquals(true, $this->readAttribute($this->db->condition, 'isNot'));
    }

    /**
     * @covers            \ZataBase\Execute\Condition::reset
     * @uses              \ZataBase\Execute\Condition
     */
    public function testReset()
    {
        $this->db->condition->where('one')->not();

        $this->assertEquals($this->readAttribute($this->db->condition, 'table')->hasColumn('one'), $this->readAttribute($this->db->condition, 'currentColumn'));
        $this->assertEquals(true, $this->readAttribute($this->db->condition, 'isNot'));

        $this->db->condition->reset();

        $this->assertEquals(false, $this->readAttribute($this->db->condition, 'currentColumn'));
        $this->assertEquals(false, $this->readAttribute($this->db->condition, 'isNot'));
    }

    /**
     * @covers            \ZataBase\Execute\Condition::equals
     * @uses              \ZataBase\Execute\Condition
     */
    public function testEquals()
    {
        $this->db->condition->where('one')->equals(1);

        $this->assertinstanceOf('\ZataBase\Execute\Condition\Equals', $this->readAttribute($this->db->condition, 'conditions')[0]);
    }

    /**
     * @covers            \ZataBase\Execute\Condition::within
     * @uses              \ZataBase\Execute\Condition
     */
    public function testWithin()
    {
        $this->db->condition->where('one')->within([1]);

        $this->assertinstanceOf('\ZataBase\Execute\Condition\Within', $this->readAttribute($this->db->condition, 'conditions')[0]);
    }

    /**
     * @covers            \ZataBase\Execute\Condition::moreThan
     * @uses              \ZataBase\Execute\Condition
     */
    public function testMoreThan()
    {
        $this->db->condition->where('one')->moreThan(0);

        $this->assertinstanceOf('\ZataBase\Execute\Condition\MoreThan', $this->readAttribute($this->db->condition, 'conditions')[0]);
    }

    /**
     * @covers            \ZataBase\Execute\Condition::lessThan
     * @uses              \ZataBase\Execute\Condition
     */
    public function testLessThan()
    {
        $this->db->condition->where('one')->lessThan(2);

        $this->assertinstanceOf('\ZataBase\Execute\Condition\LessThan', $this->readAttribute($this->db->condition, 'conditions')[0]);
    }

    /**
     * @covers            \ZataBase\Execute\Condition::between
     * @uses              \ZataBase\Execute\Condition
     */
    public function testBetween()
    {
        $this->db->condition->where('one')->between(0, 2);

        $this->assertinstanceOf('\ZataBase\Execute\Condition\Between', $this->readAttribute($this->db->condition, 'conditions')[0]);
    }

    /**
     * @covers            \ZataBase\Execute\Condition::like
     * @uses              \ZataBase\Execute\Condition
     */
    public function testLike()
    {
        $this->db->condition->where('one')->like('o_e');

        $this->assertinstanceOf('\ZataBase\Execute\Condition\Like', $this->readAttribute($this->db->condition, 'conditions')[0]);
    }
}
