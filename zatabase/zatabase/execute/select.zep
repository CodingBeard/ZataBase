/*
 * ZataBase\Execute\Select
 *
 * @category 
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Execute;

use ZataBase\Table\Column;

class Select extends Condition
{

    /**
    * Finished creating the query, check table for rows matching conditions and return the results
    * TODO: implement a resultset object
    */
    public function done() -> <\ZataBase\Execute\Results>|bool
    {
        var results;
        let results = this->getMatchedRows();
        if results->count() {
            return results;
        }
        return false;
    }

    /**
    * Get the maximum value of a column
    * @param string columnName
    */
    public function max(const string! columnName)
    {
        var column, columnKey, handle, value = [], highest = 0, highestValue = "0000-00-00";
        let column = this->table->hasColumn(columnName);
        if typeof column != "object" {
            throw new Exception("Cannot select max from column: '" . columnName . "' It does not exist.");
        }

        let columnKey = column->getKey();
        let handle = this->table->getHandle();
        if column->type == Column::DATE_TYPE {
            let value = json_decode(fgets(handle));
            while !feof(handle) {
                if strtotime(value[columnKey]) > highest {
                    let highest = strtotime(value[columnKey]);
                    let highestValue = value[columnKey];
                }
                let value = json_decode(fgets(handle));
            }
        }
        else {
            let value = json_decode(fgets(handle));
            while !feof(handle) {
                if intval(value[columnKey]) > highest {
                    let highest = intval(value[columnKey]);
                    let highestValue = value[columnKey];
                }
                let value = json_decode(fgets(handle));
            }
        }
        return highestValue;
    }

    /**
    * Get the minimum value of a column
    * @param string columnName
    */
    public function min(const string! columnName)
    {
        var column, columnKey, handle, value = [], count = 0, lowest = 0, lowestValue = "0000-00-00";
        let column = this->table->hasColumn(columnName);
        if typeof column != "object" {
            throw new Exception("Cannot select max from column: '" . columnName . "' It does not exist.");
        }

        let columnKey = column->getKey();
        let handle = this->table->getHandle();
        if column->type == Column::DATE_TYPE {
            let value = json_decode(fgets(handle));
            while !feof(handle) {
                if strtotime(value[columnKey]) < lowest || count == 0 {
                    let lowest = strtotime(value[columnKey]);
                    let lowestValue = value[columnKey];
                }
                let count++;
                let value = json_decode(fgets(handle));
            }
        }
        else {
            let value = json_decode(fgets(handle));
            while !feof(handle) {
                if intval(value[columnKey]) < lowest || count == 0 {
                    let lowest = intval(value[columnKey]);
                    let lowestValue = value[columnKey];
                }
                let count++;
                let value = json_decode(fgets(handle));
            }
        }
        return lowestValue;
    }
}