<?php

/*
 * Csv Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Helper\Csv;

class CsvTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers            \ZataBase\Helper\Csv::arrayToCsv
     * @uses              \ZataBase\Helper\Csv
     */
    public function testConstruct()
    {
        $this->assertEquals('1,2,3,4,5', Csv::arrayToCsv([1,2,3,4,5]));
        $this->assertEquals('my dog\\\'s,\"dead\"', Csv::arrayToCsv(["my dog's", '"dead"']));
    }
}
