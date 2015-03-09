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
        var value;
        if !this->table {
            throw new Exception("Cannot insert values without a selected table.");
        }
        /* Literal or multiple rows */
        if isset(values[0]) {
            if typeof values[0] == "array" {
                for value in values {
                    if isset(value[0]) {
                        this->insertLiteralValues(value);
                    }
                    else {
                        this->insertRelativeValues(value);
                    }
                }
            }
            else {
                this->insertLiteralValues(values);
            }
        }
        else {
            this->insertRelativeValues(values);
        }
    }

    /**
    * Insert a literal row into a table (all columns)
    *
    * @param Table table
    * @param array values numeric
    */
    protected function insertLiteralValues(const array! values)
    {
        if count(this->table->getColumnMap()) != count(values) {
            throw new Exception("All fields are required when using a non-associative values array");
        }
        this->{"storage"}->appendLine(this->{"config"}->schema->tablesDir . this->table->name, json_encode(values));
    }

    /**
    * Insert a relative row into a table (specific columns)
    *
    * @param Table table
    * @param array values associative
    */
    protected function insertRelativeValues(const array! values)
    {
        var columnMap, columnName, key, value;
        array valuesWithNulls;

        let columnMap = this->table->getColumnMap();

        for key, value in values {
            if !in_array(key, columnMap) {
                throw new Exception("Column: '" . key . "' does not exists in the column map of table: '" . this->table->name . "'.");
            }
        }

        let valuesWithNulls = [];

        for columnName in columnMap {
            if fetch value, values[columnName] {
                let valuesWithNulls[] = value;
            }
            else {
                let valuesWithNulls[] = null;
            }
        }
        this->{"storage"}->appendLine(this->{"config"}->schema->tablesDir . this->table->name, json_encode(valuesWithNulls));
    }
}