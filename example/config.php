<?php
/*
 * Config
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

return new ZataBase\Helper\ArrayToObject([
    "databaseDir" => __DIR__ . "/database", /* Location of the database */
    "tablesDir" => "tables/",               /* Location of table files */
]);