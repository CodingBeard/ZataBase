<?php

/*
 * QueryType Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Db;
use ZataBase\Execute\QueryType;
use ZataBase\Helper\ArrayToObject;
use ZataBase\Table;
use ZataBase\Tests\UnitUtils;

class QueryTypeTest extends PHPUnit_Framework_TestCase
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
        $this->db->testTable = new Table('Test', []);
    }

    protected function tearDown()
    {
        UnitUtils::deleteDir(__DIR__ . "/../database");
    }

    /**
     * @covers            \ZataBase\Execute\QueryType::__construct
     * @uses              \ZataBase\Execute\QueryType
     */
    public function testConstruct()
    {
        $queryType = new QueryType($this->db->getDI(), $this->db->testTable);
        $this->assertInstanceOf('\ZataBase\Execute\QueryType', $queryType);
    }
}
