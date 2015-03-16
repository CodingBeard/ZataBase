<?php
/*
 * Implode/explode vs json_encode/decode benchmark
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBaseBench\encoding;

echo PHP_EOL;

$iterations = 100;

/**/
$start = time() + microtime();
encoding::putgetcsv($iterations, ['a wild ', ' dog did', ' eat my', ' "lovely" piece', ' of meat']);
echo "csv: ",  (time() + microtime()) - $start, PHP_EOL;
/**/

/**/
$start = time() + microtime();
encoding::json_endecode($iterations, ['a wild ', ' dog did', ' eat my', ' "lovely" piece', ' of meat']);
echo "json: ",  (time() + microtime()) - $start, PHP_EOL;
/**/

/**/
$start = time() + microtime();
encoding::unserialize($iterations, ['a wild ', ' dog did', ' eat my', ' "lovely" piece', ' of meat']);
echo "serialize: ",  (time() + microtime()) - $start, PHP_EOL;
/**/

echo PHP_EOL;
