<?php

/*
 * PHPunit bootstrap file
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Tests;

use ZataBase\Exception;

class UnitUtils
{
    public static function callMethod($object, $methodName, $arguments = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $arguments);
    }

    public static function deleteDir($path)
    {
        $real = realpath($path) . '/';
        if (substr($real, 0, strlen(__DIR__)) !== __DIR__) {
            throw new Exception("Trying to delete out of the test directory");
        }

        foreach (scandir($real) as $file) {
            if ($file == '.' || $file == '..')
                continue;

            if (is_dir($real . $file)) {
                self::deleteDir($real . $file);
            }
            elseif (is_file($real . $file)) {
                unlink($real . $file);
            }
        }
        rmdir($real);
    }
}