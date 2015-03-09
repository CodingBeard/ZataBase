<?php
/*
 * Init
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Db;
use ZataBase\Table;
use ZataBase\Table\Column;

try {
    /* Create instance */
    $config = include __DIR__ . "/config.php";
    $db = new Db($config);

    /* Create a table */
    $db->schema->createTable($table = new Table('Users', [
        new Column('firstName', Column::STRING_TYPE),
        new Column('lastName', Column::STRING_TYPE),
        new Column('DoB', Column::DATE_TYPE),
    ]));

    /* Delete a table */
    $db->schema->deleteTable('Users');


    /* Literal insert with values for all columns */
    $db->execute->insert('Users')->values(['Tim', 'Marshall', '1994-07-04']);

    /* Inserting multiple rows */
    $db->execute->insert('Users')->values([
        ['John', 'doe', '1994-07-05'],
        ['Jane', 'doe', '1994-07-06'],
    ]);

    /* Relative insert, with values for specific columns */
    $db->execute->insert('Users')->values(['firstName' => 'Jim', 'lastName' => 'Doe']);

    /* Inserting multiple rows */
    $db->execute->insert('Users')->values([
        ['firstName' => 'James', 'lastName' => 'Doe'],
        ['firstName' => 'Joan', 'DoB' => '1994-07-07'],
    ]);

} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString();
}
