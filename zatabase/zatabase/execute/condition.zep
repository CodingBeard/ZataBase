/*
 * ZataBase\Execute\Condition
 *
 * @category 
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Execute;

use Zatabase\Execute\Condition\Equals;

class Condition extends QueryType {

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
    protected conditions {
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
}