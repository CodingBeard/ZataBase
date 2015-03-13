<?php

/*
 * Db Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Db;
use ZataBase\Helper\ArrayToObject;

class DbTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \ZataBase\Db
     */
    protected $db;

    protected function setUp()
    {
        $this->db = new Db(new ArrayToObject([
            "databaseDir" => __DIR__ . "/database",
            "tablesDir" => "tables/"
        ]));
    }

    /**
     * @covers            \ZataBase\Db::__construct
     * @uses              \ZataBase\Db
     * @expectedException PHPUnit_Framework_Error
     * @expectedExceptionMessage Argument 1 passed to ZataBase\Db::__construct() must be an instance of ZataBase\Helper\ArrayToObject, array given
     */
    public function testBadArgumentsOnConstruct()
    {
        new Db([]);
    }

    /**
     * @covers            \ZataBase\Db::__construct
     * @uses              \ZataBase\Db
     */
    public function testStorageInitOnConstruct()
    {
        $this->assertTrue(is_dir(__DIR__ . '/database'));
        $this->assertTrue(is_dir(__DIR__ . '/database/tables'));
    }

    /**
     * @covers            \ZataBase\Db::__construct
     * @uses              \ZataBase\Db
     */
    public function testDiOnConstruct()
    {
        $this->assertInstanceOf('\ZataBase\Helper\ArrayToObject', $this->db->config);

        $this->assertInstanceOf('\ZataBase\Storage\Adapter\File', $this->db->storage);

        $this->assertInstanceOf('\ZataBase\Execute', $this->db->execute);

        $this->assertInstanceOf('\ZataBase\Schema', $this->db->schema);
    }

    /**
     * Other functions in \ZataBase\Db are just aliases of functions we test elsewhere
     */
}
