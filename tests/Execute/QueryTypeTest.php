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

class QueryTypeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \ZataBase\Db
     */
    public $db;

    public function setUp()
    {
        $this->db = new Db(new ArrayToObject([
            "databaseDir" => __DIR__ . "/../database",
            "tablesDir" => "tables/"
        ]));
        $this->db->testTable = new Table('Test', []);
    }

    /**
     * @covers            \ZataBase\Execute\QueryType::__construct
     * @uses              \ZataBase\Execute\QueryType
     */
    public function testConstruct()
    {
        $queryType = new QueryType($this->db->testTable);
        $this->assertInstanceOf('\ZataBase\Execute\QueryType', $queryType);
    }
}
