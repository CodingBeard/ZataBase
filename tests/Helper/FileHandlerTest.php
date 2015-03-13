<?php

/*
 * File Handler Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Helper\FileHandler;
use ZataBase\Storage\Adapter\File;

class FileHandlerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ZataBase\Helper\FileHandler
     */
    protected $fileHandler;
    /**
     * @var File
     */
    protected $file;

    protected function setUp()
    {
        $this->fileHandler = new FileHandler(__DIR__ . '/../fileHandler', "c+");
    }

    protected function tearDown()
    {
        unlink(__DIR__ . '/../fileHandler');
    }

    /**
     * @covers            \ZataBase\Helper\FileHandler::__construct
     * @uses              \ZataBase\Helper\FileHandler
     */
    public function testConstruct()
    {
        $file = new FileHandler(__DIR__ . '/../fileHandler', "c+");
        $this->assertInstanceOf('\ZataBase\Helper\FileHandler', $file);
    }

    /**
     * @covers            \ZataBase\Helper\FileHandler::append
     * @uses              \ZataBase\Helper\FileHandler
     */
    public function testAppend()
    {
        $this->fileHandler->append('Appended Content');
        $this->assertEquals('Appended Content' . PHP_EOL, file_get_contents(__DIR__ . '/../fileHandler'));
    }

    /**
     * @covers            \ZataBase\Helper\FileHandler::count
     * @uses              \ZataBase\Helper\FileHandler
     * @depends testAppend
     */
    public function testCount()
    {
        $this->fileHandler->append('1');
        $this->fileHandler->append('2');
        $this->fileHandler->append('3');
        $this->assertEquals(3, $this->fileHandler->count());
    }

    /**
     * @covers            \ZataBase\Helper\FileHandler::delete
     * @uses              \ZataBase\Helper\FileHandler
     * @depends testAppend
     */
    public function testDelete()
    {
        $this->fileHandler->ftruncate(0);
        $this->fileHandler->append('1');
        $this->fileHandler->append('2');
        $this->fileHandler->append('3');
        $this->fileHandler->delete(strlen('1' . PHP_EOL));

        $this->assertEquals('1' . PHP_EOL . '3' . PHP_EOL, file_get_contents(__DIR__ . '/../fileHandler'));
    }

    /**
     * @covers            \ZataBase\Helper\FileHandler::delete
     * @uses              \ZataBase\Helper\FileHandler
     * @depends testAppend
     */
    public function testDeleteMultiple()
    {
        $this->fileHandler->ftruncate(0);
        $this->fileHandler->append('1');
        $this->fileHandler->append('2');
        $this->fileHandler->append('3');
        $this->fileHandler->delete([0, strlen('1' . PHP_EOL)]);

        $this->assertEquals('3' . PHP_EOL, file_get_contents(__DIR__ . '/../fileHandler'));
    }

    /**
     * @covers            \ZataBase\Helper\FileHandler::replace
     * @uses              \ZataBase\Helper\FileHandler
     * @depends testAppend
     */
    public function testReplace()
    {
        $this->fileHandler->ftruncate(0);
        $this->fileHandler->append('1');
        $this->fileHandler->append('2');
        $this->fileHandler->append('3');
        $this->fileHandler->replace(strlen('1' . PHP_EOL), '4');

        $this->assertEquals('1' . PHP_EOL . '4' . PHP_EOL . '3' . PHP_EOL, file_get_contents(__DIR__ . '/../fileHandler'));
    }

    /**
     * @covers            \ZataBase\Helper\FileHandler::callback
     * @uses              \ZataBase\Helper\FileHandler
     * @depends testAppend
     */
    public function testCallback()
    {
        $this->fileHandler->ftruncate(0);
        $this->fileHandler->append('1');
        $this->fileHandler->append('2');
        $this->fileHandler->append('3');
        $this->fileHandler->callback(function ($line, $increment) {
            if ($line != '2' . PHP_EOL) {
                return $line + $increment . PHP_EOL;
            }
            return '';
        }, [3]);

        $this->assertEquals('4' . PHP_EOL . '6' . PHP_EOL, file_get_contents(__DIR__ . '/../fileHandler'));
    }
}
