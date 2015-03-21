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
use ZataBase\Tests\UnitUtils;

class FileTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \ZataBase\Storage\Adapter\File
     */
    protected $file;

    protected function setUp()
    {
        $this->file = new File(__DIR__ . '/../../database');
    }

    protected function tearDown()
    {
        UnitUtils::deleteDir(__DIR__ . "/../../database");
    }

    /**
     * @covers            \ZataBase\Storage\Adapter\File::__construct
     * @uses              \ZataBase\Storage\Adapter\File
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Parameter 'directory' must be a string
     */
    public function testBadArgumentsOnConstruct()
    {
        new File([]);
    }

    /**
     * @covers            \ZataBase\Storage\Adapter\File::__construct
     * @uses              \ZataBase\Storage\Adapter\File
     */
    public function testConstruct()
    {
        $file = new File(__DIR__ . '/../../database');
        $this->assertInstanceOf('\ZataBase\Storage\Adapter\File', $file);
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
        $this->assertEquals(__DIR__ . '/../../database/', $this->file->getScope());
    }

    /**
     * @covers            \ZataBase\Storage\Adapter\File::path
     * @uses              \ZataBase\Storage\Adapter\File
     * @expectedException Exception
     * @expectedExceptionMessage Attempting to access files outside of the defined scope.
     */
    public function testOutOfScope()
    {
        $this->file->path('../../../');
    }

    /**
     * @covers            \ZataBase\Storage\Adapter\File::path
     * @uses              \ZataBase\Storage\Adapter\File
     */
    public function testScopeDir()
    {
        $this->assertEquals(__DIR__ . '/../../database/tables', $this->file->path('tables'));

        $this->assertEquals(__DIR__ . '/../../database/tables', $this->file->path(__DIR__ . '/../../database/tables'));
    }

    /**
     * @covers            \ZataBase\Storage\Adapter\File::isWritable
     * @uses              \ZataBase\Storage\Adapter\File
     */
    public function testIsWritable()
    {
        $this->assertEquals(is_writable(__DIR__ . '/../../database/tables'), $this->file->isWritable('tables'));
    }

    /**
     * @covers            \ZataBase\Storage\Adapter\File::isDir
     * @uses              \ZataBase\Storage\Adapter\File
     */
    public function testIsDir()
    {
        $this->assertEquals(is_dir(__DIR__ . '/../../database/tables'), $this->file->isDir('tables'));
    }

    /**
     * @covers            \ZataBase\Storage\Adapter\File::addDir
     * @uses              \ZataBase\Storage\Adapter\File
     */
    public function testAddDir()
    {
        $this->file->addDir('addDir');
        $this->assertTrue(is_dir(__DIR__ . '/../../database/addDir'));

        $this->file->addDir('recursive/new/dirs');
        $this->assertTrue(is_dir(__DIR__ . '/../../database/recursive/new/dirs'));
    }

    /**
     * @covers            \ZataBase\Storage\Adapter\File::scanDir
     * @uses              \ZataBase\Storage\Adapter\File
     */
    public function testScanDir()
    {
        $this->file->addDir('scanDir/a');
        $this->file->addDir('scanDir/b');
        $this->file->addDir('scanDir/c');

        $this->assertEquals(['a', 'b', 'c'], $this->file->scanDir('scanDir'));
    }

    /**
     * @covers            \ZataBase\Storage\Adapter\File::removeDir
     * @uses              \ZataBase\Storage\Adapter\File
     */
    public function testRemoveDir()
    {
        $this->file->removeDir('addDir');
        $this->assertFalse(is_dir(__DIR__ . '/../../database/addDir'));

        $this->file->removeDir('recursive/');
        $this->assertFalse(is_dir(__DIR__ . '/../../database/recursive'));
    }

    /**
     * @covers            \ZataBase\Storage\Adapter\File::isFile
     * @uses              \ZataBase\Storage\Adapter\File
     */
    public function testIsFile()
    {
        touch(__DIR__ . '/../../database/file');
        $this->assertEquals(is_file(__DIR__ . '/../../database/file'), $this->file->isFile('file'));
    }

    /**
     * @covers            \ZataBase\Storage\Adapter\File::touch
     * @uses              \ZataBase\Storage\Adapter\File
     */
    public function testTouch()
    {
        $this->file->touch('touch');
        $this->assertTrue(is_file(__DIR__ . '/../../database/touch'));

        $this->file->touch('recursive/new/dirs/touch');
        $this->assertTrue(is_file(__DIR__ . '/../../database/recursive/new/dirs/touch'));
    }

    /**
     * @covers            \ZataBase\Storage\Adapter\File::setFile
     * @uses              \ZataBase\Storage\Adapter\File
     */
    public function testSetFile()
    {
        $this->file->setFile('setFile', 'Content');
        $this->assertEquals(file_get_contents(__DIR__ . '/../../database/setFile'), 'Content' . PHP_EOL);

        $this->file->setFile('recursive/new/dirs/setFile', 'Content');
        $this->assertEquals(file_get_contents(__DIR__ . '/../../database/recursive/new/dirs/setFile'), 'Content' . PHP_EOL);
    }

    /**
     * @covers            \ZataBase\Storage\Adapter\File::getFile
     * @uses              \ZataBase\Storage\Adapter\File
     */
    public function testGetFile()
    {
        $this->file->setFile('getFile', 'Content');
        $this->assertEquals($this->file->getFile('getFile'), 'Content' . PHP_EOL);

        $this->file->setFile('recursive/new/dirs/getFile', 'Content');
        $this->assertEquals($this->file->getFile('recursive/new/dirs/getFile'), 'Content' . PHP_EOL);
    }

    /**
     * @covers            \ZataBase\Storage\Adapter\File::getHandle
     * @uses              \ZataBase\Storage\Adapter\File
     */
    public function testGetHandle()
    {
        $handle = $this->file->getHandle('getHandle');
        $this->assertTrue(is_file(__DIR__ . '/../../database/getHandle'));

        $this->assertInstanceOf('\ZataBase\Helper\FileHandler', $handle);
    }

    /**
     * @covers            \ZataBase\Storage\Adapter\File::removeFile
     * @uses              \ZataBase\Storage\Adapter\File
     */
    public function testRemoveFile()
    {
        file_put_contents(__DIR__ . '/../../database/removeFile', ' ');
        $this->file->removeFile('removeFile');
        $this->assertFalse(is_file(__DIR__ . '/../../database/removeFile'));
    }
}
