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
        let results = this->table->selectRows(this->conditions);
        if results->count() {
            return results;
        }
        return false;
    }

    /**
    * Get the maximum value of a column
    * @param string columnName
    */
    public function max(const string! columnName) -> string|int
    {
        var column, columnKey, rows, row, highest = 0, highestValue = "0000-00-00";

        let column = this->table->hasColumn(columnName);
        if typeof column != "object" {
            throw new Exception("Cannot select max from column: '" . columnName . "' It does not exist.");
        }

        let columnKey = column->getKey();
        let rows = this->table->selectRows();

        if column->type == Column::DATE_TYPE {
            for row in iterator(rows) {
                if strtotime(row[columnKey]) > highest {
                    let highest = strtotime(row[columnKey]);
                    let highestValue = row[columnKey];
                }
            }
        }
        else {
            for row in iterator(rows) {
                if intval(row[columnKey]) > highest {
                    let highest = intval(row[columnKey]);
                    let highestValue = row[columnKey];
                }
            }
        }
        return highestValue;
    }

    /**
    * Get the minimum value of a column
    * @param string columnName
    */
    public function min(const string! columnName) -> string|int
    {
        var column, columnKey, rows, key, row, lowest = 0, lowestValue = "9999-99-99";

        let column = this->table->hasColumn(columnName);
        if typeof column != "object" {
            throw new Exception("Cannot select max from column: '" . columnName . "' It does not exist.");
        }

        let columnKey = column->getKey();
        let rows = this->table->selectRows();

        if column->type == Column::DATE_TYPE {
            for key, row in iterator(rows) {
                if strtotime(row[columnKey]) < lowest || key == 0 {
                    let lowest = strtotime(row[columnKey]);
                    let lowestValue = row[columnKey];
                }
            }
        }
        else {
            for key, row in iterator(rows) {
                if intval(row[columnKey]) < lowest || key == 0 {
                    let lowest = intval(row[columnKey]);
                    let lowestValue = row[columnKey];
                }
            }
        }
        return lowestValue;
    }
}