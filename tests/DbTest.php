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
    public function testConstruct()
    {
        new Db(new ArrayToObject([
            "databaseDir" => __DIR__ . "/database",
            "tablesDir" => "tables/"
        ]));
    }
}
