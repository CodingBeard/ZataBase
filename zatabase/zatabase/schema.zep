/*
 * ZataBase\Schema
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase;

use ZataBase\Di\Injectable;

class Schema extends Injectable {

    /**
    * Create a table
    * @param Table table
    */
    public function createTable(<Table> table)
    {
        if !this->getTable(table->name) {
            table->create();
        }
        else {
            throw new Exception("Table: '" . table->name . "' already exists.");
        }
    }

    /**
    * Instance a table from the tables' file
    * @param string name
    */
    public function getTable(const string! name) -> <Table>|bool
    {
        var row;
        this->{"traverser"}->setTable("schema");
        let row = this->{"traverser"}->findRow("name", name);
        if row {
            return new table(row->name, row->columns, row->increment, row->relationships, row->id);
        }
        else {
            return false;
        }
    }

    /**
    * Delete a table from the tables' file
    * @param string name
    */
    public function deleteTable(const string! name)
    {
        var table;
        let table = this->getTable(name);
        if table {
            table->delete();
        }
        else {
            return false;
        }
    }

}