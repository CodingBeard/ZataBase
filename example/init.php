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
        new Column('id', Column::INT_TYPE, [Column::INCREMENT_FLAG]),
        new Column('firstName', Column::STRING_TYPE),
        new Column('lastName', Column::STRING_TYPE),
        new Column('DoB', Column::DATE_TYPE),
    ]));

    /* Literal insert with values for all columns */
    $db->insert('Users')->values([1, 'Josh', 'Doe', '1994-07-04']);

    /* Inserting multiple rows */
    $db->insert('Users')->values([
        [2, 'John', 'doe', '1994-07-05'],
        [3, 'Jane', 'doe', '1994-07-06'],
    ]);

    /* Relative insert, with values for specific columns */
    $db->insert('Users')->columns(['firstName', 'lastName'])->values(['Jim', 'Doe']);

    /* Inserting multiple rows */
    $db->insert('Users')->columns(['firstName', 'lastName'])->values([
        ['James', 'Doe'],
        ['Joan', 'Doe'],
    ]);

    /* Select row(s), done tells the query builder we've finished adding conditions and to return the result */
    $rows = $db->select('Users')->done();

    /* Select row(s), conditionally */
    $row = $db->select('Users')
        ->where('firstName')->equals('Josh')
        ->done();

    /* andWhere is just an alias for where, they can be used interchangeably */
    $row = $db->select('Users')
        ->where('firstName')->equals('Josh')
        ->andWhere('lastName')->equals('Doe')
        ->done();

    /* not() can be set after where/andWhere to 'Not' the following condition */
    $not = $db->select('Users')
        ->where('firstName')->not()->equals('James')
        ->done();

    /* Where the column is within an array of values */
    $within = $db->select('Users')
        ->where('firstName')->within(['Jim', 'Joan'])
        ->done();

    /* Where the column is more than the supplied value,
     * DATE_TYPE will be strtotime(), all else will be intval()
     * If a second parameter is set to true, it will be inclusive E.G. >=
     */
    $moreThan = $db->select('Users')
        ->where('DoB')->moreThan('1994-07-01')
        ->done();

    /* Where the column is more than the supplied value,
     * DATE_TYPE will be strtotime(), all else will be intval()
     * If a second parameter is set to true, it will be inclusive E.G. <=
     */
    $lessThan = $db->select('Users')
        ->where('DoB')->lessThan('1994-07-07', true)
        ->done();

    /* Where the column is between the supplied values,
     * DATE_TYPE will be strtotime(), all else will be intval()
     * If a second parameter is set to true, it will be inclusive E.G. <=
     */
    $between = $db->select('Users')
        ->where('DoB')->between('1994-07-01', '1994-07-06')
        ->done();

    /* Similar to mysql's LIKE, % and _ are multiple and single character wildcards */
    $like = $db->select('Users')
        ->where('firstName')->like('j%n')
        ->done();

    /* Access results */
    foreach ($rows as $row) {
        print_r($row);
    }

    /* Update row(s) */
    $db->update('Users')->setColumns(['lastName'])->values(['Don'])->done();

    /* Update row(s), conditionally. Any of the above selectable conditions can be used here */
    $db->update('Users')->setColumns(['lastName'])->values(['Don'])->where('firstName')->equals('Jim')->done();

    /* Delete row(s), conditionally. Any of the above selectable conditions can be used here */
    //$db->delete('Users')->where('firstName')->equals('Jim')->done();

    /* Delete row(s) */
    //$db->delete('Users')->done();

} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString();
}
