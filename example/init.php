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

    /* Delete a table */
    $db->schema->deleteTable('Users');

    /* Create a table */
    $db->schema->createTable(new Table('Users', [
        new Column('firstName', Column::STRING_TYPE),
        new Column('lastName', Column::STRING_TYPE),
        new Column('DoB', Column::DATE_TYPE),
    ]));

    /* Literal insert with values for all columns */
    $db->execute->insert('Users')->values(['Josh', 'Doe', '1994-07-04']);

    /* Inserting multiple rows */
    $db->execute->insert('Users')->values([
        ['John', 'doe', '1994-07-05'],
        ['Jane', 'doe', '1994-07-06'],
    ]);

    /* Delete row(s) */
    $db->execute->delete('Users')->done();

    /* Relative insert, with values for specific columns */
    $db->execute->insert('Users')->values(['firstName' => 'Jim', 'lastName' => 'Doe']);

    /* Delete row(s), conditionally */
    $db->execute->delete('Users')->where('firstName')->equals('Jim')->done();

    /* Inserting multiple rows */
    $db->execute->insert('Users')->values([
        ['firstName' => 'James', 'lastName' => 'Doe'],
        ['firstName' => 'Joan', 'DoB' => '1994-07-07'],
    ]);

    /* Select row(s) */
    $rows = $db->execute->select('Users')->done();

    /* Select row(s), conditionally */
    $row = $db->execute->select('Users')->where('firstName')->equals('Josh')->done();

    print_r($rows);

} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString();
}
