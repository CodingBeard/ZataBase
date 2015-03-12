<?php

/*
 * Storage Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Storage\Adapter\File;

class FileTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \ZataBase\Storage\Adapter\File
     */
    protected $file;

    protected function setUp()
    {
        $this->file = new File(__DIR__ . '/database');
    }

    protected function tearDown()
    {
        exec("rm -rf " . __DIR__ . '/database');
    }

    /**
     * @covers            \ZataBase\Storage\Adapter\File::__construct
     * @uses              \ZataBase\Storage\Adapter\File
     * @expectedException Exception
     * @expectedExceptionMessage Cannot create database in dir: '/root'. Bad Permissions.
     */
    public function testBadPermissions()
    {
        new File('/root/database');
    }

    /**
     * @covers            \ZataBase\Storage\Adapter\File::__construct
     * @uses              \ZataBase\Storage\Adapter\File
     */
    public function testScope()
    {
        $this->assertEquals(__DIR__ . '/database/', $this->file->getScope());
    }


}
