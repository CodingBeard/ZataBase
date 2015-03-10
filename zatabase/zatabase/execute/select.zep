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

use Zatabase\Execute\Condition\Equals;

class Select extends QueryType
{

    /**
    * Current column Key
    * @var string
    */
    protected currentColumnKey {
        set, get
    };

    /**
    * Conditions
    * @var array
    */
    protected conditions = [] {
        set, get
    };

    /**
    * Select a column to apply a condition to
    * @param string columnName
    */
    public function where(const string! columnName) -> <Select>
    {
        var key;
        let key = this->table->hasColumn(columnName);
        if typeof key != "int" {
            throw new Exception("Cannot select column: " . columnName . "' It does not exist.");
        }

        let this->currentColumnKey = key;
        return this;
    }

    /**
    * Select a column to apply a condition to
    * @param string columnName
    */
    public function equals(const var value) -> <Select>
    {
        if this->currentColumnKey === false {
            throw new Exception("Cannot apply a condition without selecting a column first.");
        }

        let this->conditions[] = new Equals(this->currentColumnKey, value);
        let this->currentColumnKey = false;
        return this;
    }

    /**
    * Finished creating the query, check table for rows matching conditions
    * TODO: implement a resultset object
    */
    public function done() -> array|bool
    {
        var handle, row, condition, results;
        let results = [];
        let handle = this->table->getHandle();
        if count(this->conditions) {
            while !feof(handle) {
                let row = fgets(handle);
                for condition in this->conditions {
                    let row = json_decode(row);
                    if condition->matches(row) {
                        let results[] = row;
                    }
                }
            }
        }
        else {
            while !feof(handle) {
                let results[] = fgets(handle);
            }
        }
        if count(results) {
            return results;
        }
        return false;
    }
}