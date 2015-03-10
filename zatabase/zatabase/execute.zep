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

class Execute extends Injectable {

    /**
    * Set a table to insert into
    *
    * @param string tableName
    */
    public function insert(const string! tableName) -> <Execute\Insert>
    {
        var table;

        let table = this->{"schema"}->getTable(tableName);
        if !table {
            throw new Exception("Cannot insert into table: '" . tableName . "'. It does not exist.");
        }
        return new Insert(table);
    }

    /**
    * Set a table to select from
    *
    * @param array parameters
    */
    public function select(const string! tableName) -> <Execute\Select>
    {
        var table;

        let table = this->{"schema"}->getTable(tableName);
        if !table {
            throw new Exception("Cannot select from table: '" . tableName . "'. It does not exist.");
        }
        return new Select(table);
    }

}