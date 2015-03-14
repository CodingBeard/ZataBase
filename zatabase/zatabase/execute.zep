/*
 * Execute
 *
 * @category 
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase;

use ZataBase\Di\Injectable;
use ZataBase\Execute\Insert;
use ZataBase\Execute\Select;
use ZataBase\Execute\Delete;
use ZataBase\Execute\Update;

class Execute extends Injectable {

    /**
    * Set a table to insert into
    *
    * @param string tableName
    */
    public function insert(const string! tableName) -> <Execute\Insert>
    {
        var table, query;

        let table = this->{"schema"}->getTable(tableName);
        if !table {
            throw new Exception("Cannot insert into table: '" . tableName . "'. It does not exist.");
        }
        let query = new Insert(table);
        query->setDI(this->getDI());
        return query;
    }

    /**
    * Set a table to select from
    *
    * @param array parameters
    */
    public function select(const string! tableName) -> <Execute\Select>
    {
        var table, query;

        let table = this->{"schema"}->getTable(tableName);
        if !table {
            throw new Exception("Cannot select from table: '" . tableName . "'. It does not exist.");
        }
        let query = new Select(table);
        query->setDI(this->getDI());
        return query;
    }

    /**
    * Set a table to delete from
    *
    * @param array parameters
    */
    public function delete(const string! tableName) -> <Execute\Select>
    {
        var table, query;

        let table = this->{"schema"}->getTable(tableName);
        if !table {
            throw new Exception("Cannot delete from table: '" . tableName . "'. It does not exist.");
        }
        let query = new Delete(table);
        query->setDI(this->getDI());
        return query;
    }

    /**
    * Set a table to update from
    *
    * @param array parameters
    */
    public function update(const string! tableName) -> <Execute\Select>
    {
        var table, query;

        let table = this->{"schema"}->getTable(tableName);
        if !table {
            throw new Exception("Cannot update table: '" . tableName . "'. It does not exist.");
        }
        let query = new Update(table);
        query->setDI(this->getDI());
        return query;
    }

}