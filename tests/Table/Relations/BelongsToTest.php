<?php

/*
 * BelongsTo Test
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

class BelongsToTest extends PHPUnit_Framework_TestCase
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
            "databaseDir" => __DIR__ . "/../../database",
            "tablesDir" => "tables/"
        ]));

        $this->db->deleteTable('Users');

        $this->db->createTable(new Table('Users', [
            new Column('id', Column::INT_TYPE, [Column::INCREMENT_FLAG]),
            new Column('firstName', Column::STRING_TYPE),
            new Column('lastName', Column::STRING_TYPE),
            new Column('DoB', Column::DATE_TYPE),
        ]));

        $this->db->deleteTable('Hats');

        $this->db->createTable(new Table('Hats',
            [
                new Column('id', Column::INT_TYPE, [Column::INCREMENT_FLAG]),
                new Column('user_id', Column::INT_TYPE),
                new Column('name', Column::STRING_TYPE),
            ],
            [
                new BelongsTo('Users', 'id', 'user_id')
            ]));
    }

    protected function tearDown()
    {
        UnitUtils::deleteDir(__DIR__ . "/../../database");
    }

    /**
     * @covers            \ZataBase\Table\Relations\BelongsTo::__construct
     * @uses              \ZataBase\Table\Relations\BelongsTo
     */
    public function testConstruct()
    {
        $belongsTo = new BelongsTo('Users', 'id', 'user_id', 'Hats');

        $this->assertInstanceOf('\ZataBase\Table\Relations\BelongsTo', $belongsTo);

        $this->assertEquals(
            ['Users', 'id', 'user_id', 'Hats'],
            [$belongsTo->getParentTable(), $belongsTo->getParentColumn(), $belongsTo->getChildColumn(), $belongsTo->getChildTable()]
        );
    }
}
