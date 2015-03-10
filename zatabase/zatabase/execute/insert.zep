/*
 * ZataBase\Execute\Insert
 *
 * @category 
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Execute;

class Insert extends QueryType
{

    /**
    * Columns to insert into
    * @var string
    */
    protected columns = false {
        set, get
    };

    /**
    * Flesh out a row with nulls where it is missing columns
    *
    * @param array columns
    */
    public function columns(const array! columns) -> <Insert>
    {
        var column;
        let this->columns = [];
        for column in columns {
            let this->columns[] = this->table->columnKey(column);
        }
        return this;
    }

    /**
    * Insert row/s into a table
    *
    * Values should be multi-dimensional if multiple rows are being inserted
    * If inserting values for every column use a numeric array
    * If only inserting into some columns use an associative array with the
    * column names as keys
    *
    * @param array values
    */
    public function values(const array! values)
    {
        var value, rows = [];
        if !this->table {
            throw new Exception("Cannot insert values without a selected table.");
        }
        /* Literal or multiple rows */
        if isset(values[0]) {
            if typeof values[0] == "array" {
                for value in values {
                    if isset(value[0]) && this->columns {
                        let rows[] = this->fillNulls(value);
                    }
                    else {
                        let rows[] = value;
                    }
                }
                this->table->insertRows(rows);
            }
            elseif this->columns {
                this->table->insertRow(this->fillNulls(values));
            }
            else {
                this->table->insertRow(values);
            }
        }
        else {
            this->table->insertRow(this->fillNulls(values));
        }
    }

    /**
    * Flesh out a row with nulls where it is missing columns
    *
    * @param array values associative
    */
    protected function fillNulls(const array! values) -> array
    {
        var key = 0, value;
        array valuesWithNulls;

        let valuesWithNulls = [];

        while key < count(this->table->getColumnMap()) {
            let valuesWithNulls[] = null;
            let key++;
        }

        for key, value in values {
            let valuesWithNulls[this->columns[key]] = value;
        }

        return valuesWithNulls;
    }
}