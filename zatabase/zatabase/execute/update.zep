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

class Update extends Condition
{

    /**
    * Columns to update
    * @var string
    */
    protected columns;

    /**
    * Values to update with
    * @var string
    */
    protected values;

    /**
    * Which columns to update
    *
    * @param array columns
    */
    public function setColumns(const var columns) -> <Update>
    {
        var arraycolumns, column;
        if typeof columns != "array" {
            let arraycolumns = [columns];
        }
        else {
            let arraycolumns = columns;
        }
        let this->columns = [];
        for column in arraycolumns {

            if !this->table->hasColumn(column) {
                throw new Exception("You cannot select a column to update that does not exist.");
            }

            let this->columns[] = this->table->columnKey(column);
        }
        return this;
    }

    /**
    * Set the values to update with
    *
    * @param array values
    */
    public function values(const var values) -> <Update>
    {
        var arrayvalues;

        if typeof values != "array" {
            let arrayvalues = [values];
        }
        else {
            let arrayvalues = values;
        }

        if !this->table {
            throw new Exception("Cannot update values without a selected table.");
        }
        if count(arrayvalues) != count(this->columns) {
            throw new Exception("Number of values must match number of columns.");
        }

        let this->values = arrayvalues;
        return this;
    }

    /**
    * Finished creating the query, check table for rows matching conditions and update the results
    */
    public function done() -> bool
    {
        var results;
        let results = this->table->selectRows(this->conditions);

        if results->count() {
            this->table->file->callback(function (var line, var columns, var values) {

                var columnCount, columnKey, row;

                let row = [];
                let row = json_decode(line);

                for columnCount, columnKey in columns {
                    let row[columnKey] = values[columnCount];
                }
                return json_encode(row) . PHP_EOL;

            }, [this->columns, this->values], results->rows);

        }

        return false;
    }
}