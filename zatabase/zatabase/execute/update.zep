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
    protected columns {
        get
    };

    /**
    * Values to update with
    * @var string
    */
    protected values {
        set, get
    };

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
            let this->columns[] = this->table->columnKey(column);
        }
        return this;
    }

    /**
    * Finished creating the query, check table for rows matching conditions and update the results
    * TODO: Implement callback instead of rewriting the file for every updated row
    */
    public function done() -> bool
    {
        var results, rowCount, result, offset, columnCount, columnKey;
        let results = this->table->selectRows(this->conditions);

        if results->count() {

            for rowCount, result in iterator(results) {

                let offset = results->getOffset(rowCount);

                for columnCount, columnKey in this->columns {
                    let result[columnKey] = this->values[columnCount];
                }

                this->table->file->replace(offset, json_encode(result));
            }
        }
        return false;
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
}