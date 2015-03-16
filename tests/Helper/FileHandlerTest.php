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
     * @covers            \ZataBase\Helper\FileHandler::appendRaw
     * @uses              \ZataBase\Helper\FileHandler
     */
    public function testAppendRaw()
    {
        $this->fileHandler->appendRaw('Appended Content');
        $this->assertEquals('Appended Content' . PHP_EOL, file_get_contents(__DIR__ . '/../fileHandler'));
    }

    /**
     * @covers            \ZataBase\Helper\FileHandler::count
     * @uses              \ZataBase\Helper\FileHandler
     */
    public function testCount()
    {
        $this->fileHandler->appendRaw('1');
        $this->fileHandler->appendRaw('2');
        $this->fileHandler->appendRaw('3');
        $this->assertEquals(3, $this->fileHandler->count());
    }

    /**
     * @covers            \ZataBase\Helper\FileHandler::delete
     * @uses              \ZataBase\Helper\FileHandler
     */
    public function testDelete()
    {
        $this->fileHandler->ftruncate(0);
        $this->fileHandler->appendRaw('1');
        $this->fileHandler->appendRaw('2');
        $this->fileHandler->appendRaw('3');
        $this->fileHandler->delete(strlen('1' . PHP_EOL));

        $this->assertEquals('1' . PHP_EOL . '3' . PHP_EOL, file_get_contents(__DIR__ . '/../fileHandler'));
    }

    /**
     * @covers            \ZataBase\Helper\FileHandler::delete
     * @uses              \ZataBase\Helper\FileHandler
     */
    public function testDeleteMultiple()
    {
        $this->fileHandler->ftruncate(0);
        $this->fileHandler->appendRaw('1');
        $this->fileHandler->appendRaw('2');
        $this->fileHandler->appendRaw('3');
        $this->fileHandler->delete([0, strlen('1' . PHP_EOL)]);

        $this->assertEquals('3' . PHP_EOL, file_get_contents(__DIR__ . '/../fileHandler'));
    }

    /**
     * @covers            \ZataBase\Helper\FileHandler::replace
     * @uses              \ZataBase\Helper\FileHandler
     */
    public function testReplace()
    {
        $this->fileHandler->ftruncate(0);
        $this->fileHandler->appendRaw('1');
        $this->fileHandler->appendRaw('2');
        $this->fileHandler->appendRaw('3');
        $this->fileHandler->replace(strlen('1' . PHP_EOL), '4');

        $this->assertEquals('1' . PHP_EOL . '4' . PHP_EOL . '3' . PHP_EOL, file_get_contents(__DIR__ . '/../fileHandler'));
    }

    /**
     * @covers            \ZataBase\Helper\FileHandler::callback
     * @uses              \ZataBase\Helper\FileHandler
     */
    public function testCallback()
    {
        $this->fileHandler->ftruncate(0);
        $this->fileHandler->appendRaw('1');
        $this->fileHandler->appendRaw('2');
        $this->fileHandler->appendRaw('3');
        $this->fileHandler->callback(function ($line, $increment) {
            if ($line != '2' . PHP_EOL) {
                return $line + $increment . PHP_EOL;
            }
            return '';
        }, [3]);

        $this->assertEquals('4' . PHP_EOL . '6' . PHP_EOL, file_get_contents(__DIR__ . '/../fileHandler'));
    }

    /**
     * @covers            \ZataBase\Helper\FileHandler::callback
     * @uses              \ZataBase\Helper\FileHandler
     */
    public function testCallbackOffsets()
    {
        $this->fileHandler->ftruncate(0);
        $this->fileHandler->appendRaw('1');
        $this->fileHandler->appendRaw('2');
        $this->fileHandler->appendRaw('3');
        $this->fileHandler->callback(function ($line, $increment) {
            return $line + $increment . PHP_EOL;
        }, [3], [0, (strlen('1' . PHP_EOL) + strlen('2' . PHP_EOL))]);

        $this->assertEquals('4' . PHP_EOL . '2' . PHP_EOL . '6' . PHP_EOL, file_get_contents(__DIR__ . '/../fileHandler'));
    }

    /**
     * @covers            \ZataBase\Helper\FileHandler::appendcsv
     * @uses              \ZataBase\Helper\FileHandler
     */
    public function testGetCsv()
    {
        file_put_contents(__DIR__ . '/../fileHandler', '1,2,3' . PHP_EOL);

        $this->assertEquals([1, 2, 3], $this->fileHandler->getcsv(0));
    }

    /**
     * @covers            \ZataBase\Helper\FileHandler::appendcsv
     * @uses              \ZataBase\Helper\FileHandler
     */
    public function testAppendCsv()
    {
        $this->fileHandler->ftruncate(0);
        $this->fileHandler->appendcsv([4, 2, 6]);

        $this->assertEquals([4, 2, 6], $this->fileHandler->getcsv(0));
    }

    /**
     * @covers            \ZataBase\Helper\FileHandler::appendcsvs
     * @uses              \ZataBase\Helper\FileHandler
     */
    public function testAppendCsvs()
    {
        $this->fileHandler->ftruncate(0);
        $this->fileHandler->appendcsvs([
            [4, 2, 6],
            [1, 2, 3]
        ]);

        $this->assertEquals([4, 2, 6], $this->fileHandler->getcsv(0));
        $this->assertEquals([1, 2, 3], $this->fileHandler->getcsv(strlen('4,2,6' . PHP_EOL)));
    }

    /**
     * @covers            \ZataBase\Helper\FileHandler::appendcsv
     * @uses              \ZataBase\Helper\FileHandler
     */
    public function testPutCsv()
    {
        $this->fileHandler->ftruncate(0);
        file_put_contents(__DIR__ . '/../fileHandler', '1,2,3' . PHP_EOL);
        $this->fileHandler->putcsv(strlen('1,2,3' . PHP_EOL), [4, 2, 6]);

        $this->assertEquals([4, 2, 6], $this->fileHandler->getcsv(strlen('1,2,3' . PHP_EOL)));
    }
}
