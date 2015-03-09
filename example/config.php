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

return [
  "databaseDir" => __DIR__ . "/database",   /* Location of the database */
  "schema" => [
    "definitionFile" => "schema",           /* Name of file table definitions are stored in */
    "tablesDir" => "tables/"                /* Location of table files */
  ]
];