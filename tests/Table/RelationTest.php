<?php

/*
 * Relation Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Db;
use ZataBase\Helper\ArrayToObject;
use ZataBase\Table;
use ZataBase\Table\Column;
use ZataBase\Table\Relations\BelongsTo;
use ZataBase\Tests\UnitUtils;

class RelationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \ZataBase\Db
     */
    protected $db;

    /**
     * Will test table classes as a property of the database so it can access the DependencyInjector
     */
    protected function setUp()
    {
        $this->db = new Db(new ArrayToObject([
            "databaseDir" => __DIR__ . "/../database"
        ]));

        $this->db->createTable(new Table('Users', [
            new Column('id', Column::INT_TYPE, [Column::INCREMENT_FLAG]),
            new Column('firstName', Column::STRING_TYPE),
            new Column('lastName', Column::STRING_TYPE),
            new Column('DoB', Column::DATE_TYPE),
        ]));

        $this->db->createTable(new Table('Hats', [
            new Column('id', Column::INT_TYPE, [Column::INCREMENT_FLAG]),
            new Column('user_id', Column::INT_TYPE),
            new Column('name', Column::STRING_TYPE),
        ], [
            new BelongsTo('Users', 'id', 'user_id')
        ]));
    }

    protected function tearDown()
    {
        UnitUtils::deleteDir(__DIR__ . "/../database");
    }

    /*
     * @covers            \ZataBase\Table::addRelation
     * @uses              \ZataBase\Table
     * @expectedException Exception
     * @expectedExceptionMessage You cannot add a relation if the parent table is non-existent.
     *
    public function testAddRelationNoParent()
    {
        $table = $this->db->schema->getTable('Hats');

        $table->setRelations(null);

        $table->addRelation(new BelongsTo('Non', 'id', 'user_id'));
    }

    /*
     * @covers            \ZataBase\Table::addRelation
     * @uses              \ZataBase\Table
     * @expectedException Exception
     * @expectedExceptionMessage You cannot add a relation if the parent table's column is non-existent.
     *
    public function testAddRelationNoParentColumn()
    {
        $table = $this->db->schema->getTable('Hats');

        $table->setRelations(null);

        $table->addRelation(new BelongsTo('Users', 'Non', 'user_id'));
    }*/

    /**
     * @covers            \ZataBase\Table::addRelation
     * @uses              \ZataBase\Table
     * @expectedException Exception
     * @expectedExceptionMessage You cannot add a relation to this table with a non-existent child column.
     */
    public function testAddRelationNoChildColumn()
    {
        $table = $this->db->schema->getTable('Hats');

        $table->setRelations(null);

        $table->addRelation(new BelongsTo('Users', 'id', 'Non'));
    }

    /**
     * @covers            \ZataBase\Table::addRelation
     * @uses              \ZataBase\Table
     */
    public function testAddRelation()
    {
        $table = $this->db->schema->getTable('Hats');

        $table->setRelations(null);

        $table->addRelation(new BelongsTo('Users', 'id', 'user_id'));

        $this->assertEquals([
            new Table\Relations\BelongsTo('Users', 'id', 'user_id', 'Hats')
        ], $this->db->schema->getTable('Hats')->getRelations());
    }
}
