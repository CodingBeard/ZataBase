<?php

/*
 * Schema Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Db;
use ZataBase\Helper\ArrayToObject;
use ZataBase\Storage\Adapter\File;

class SchemaTest extends PHPUnit_Framework_TestCase
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

    public function testConstruct()
    {

    }

}
