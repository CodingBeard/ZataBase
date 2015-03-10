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

use ZataBase\Execute\Condition\Exception;
use Zatabase\Execute\Condition\Equals;
use Zatabase\Execute\Condition\Within;
use Zatabase\Execute\Condition\MoreThan;
use Zatabase\Execute\Condition\LessThan;
use Zatabase\Execute\Condition\Between;
use Zatabase\Execute\Condition\Like;

class Condition extends QueryType {

    /**
    * Current column
    * @var string
    */
    protected currentColumn {
        set, get
    };

    /**
    * If the current condition is a Not
    * @var string
    */
    protected isNot = false {
        set, get
    };

    /**
    * Conditions
    * @var array
    */
    protected conditions {
        set, get
    };

    public function reset()
    {
        let this->currentColumn = false;
        let this->isNot = false;
    }

    /**
    * Select a column to apply a condition to
    * @param string columnName
    */
    public function where(const string! columnName) -> <Condition>
    {
        var column;
        let column = this->table->hasColumn(columnName);
        if typeof column != "object" {
            throw new Exception("Cannot select column: " . columnName . "' It does not exist.");
        }

        let this->currentColumn = column;
        return this;
    }

    /**
    * Alias of where
    * @param string columnName
    */
    public function andWhere(const string! columnName) -> <Condition>
    {
        return this->where(columnName);
    }

    /**
    * Reverse the next condition 'Not it'
    * @param string columnName
    */
    public function not() -> <Condition>
    {
        let this->isNot = true;
        return this;
    }

    /**
    * Apply an equals condition to the selected column
    * @param mixed value
    */
    public function equals(const var value) -> <Condition>
    {
        if this->currentColumn === false {
            throw new Exception("Cannot apply a condition without selecting a column first.");
        }

        let this->conditions[] = new Equals(this->isNot, this->currentColumn, value);
        this->reset();
        return this;
    }

    /**
    * Apply a within condition to the selected column
    * @param mixed value
    */
    public function within(const array! values) -> <Condition>
    {
        if this->currentColumn === false {
            throw new Exception("Cannot apply a condition without selecting a column first.");
        }

        let this->conditions[] = new Within(this->isNot, this->currentColumn, values);
        this->reset();
        return this;
    }

    /**
    * Apply a more than condition to the selected column
    * @param mixed value
    * @param bool equalTo
    */
    public function moreThan(const var! value, const bool! equalTo = false) -> <Condition>
    {
        if this->currentColumn === false {
            throw new Exception("Cannot apply a condition without selecting a column first.");
        }

        let this->conditions[] = new MoreThan(this->isNot, this->currentColumn, value, equalTo);
        this->reset();
        return this;
    }

    /**
    * Apply a less than condition to the selected column
    * @param mixed value
    * @param bool equalTo
    */
    public function lessThan(const var! value, const bool! equalTo = false) -> <Condition>
    {
        if this->currentColumn === false {
            throw new Exception("Cannot apply a condition without selecting a column first.");
        }

        let this->conditions[] = new LessThan(this->isNot, this->currentColumn, value, equalTo);
        this->reset();
        return this;
    }

    /**
    * Apply a less than condition to the selected column
    * @param mixed value
    * @param bool equalTo
    */
    public function between(const var! lowerValue, const var! higherValue, const bool! equalTo = false) -> <Condition>
    {
        if this->currentColumn === false {
            throw new Exception("Cannot apply a condition without selecting a column first.");
        }

        let this->conditions[] = new Between(this->isNot, this->currentColumn, lowerValue, higherValue, equalTo);
        this->reset();
        return this;
    }

    /**
    * Perform a like comparison with % and _ wildcards
    * @param mixed value
    */
    public function like(const var value) -> <Condition>
    {
        if this->currentColumn === false {
            throw new Exception("Cannot apply a condition without selecting a column first.");
        }

        let this->conditions[] = new Like(this->isNot, this->currentColumn, value);
        this->reset();
        return this;
    }

    /**
    * Finished creating the query, check table for rows matching conditions
    */
    public function getMatchedRows() -> array|bool
    {
        var handle, row, condition, rows;
        bool match = true;
        let rows = [];
        let handle = this->table->getHandle();
        if typeof this->conditions == "array" {
            while !feof(handle) {
                let row = fgets(handle);
                if strlen(row) {
                    let match = true;
                    let row = json_decode(row);
                    for condition in this->conditions {
                        if !condition->matches(row) {
                            let match = false;
                        }
                    }
                    if match {
                        let rows[] = row;
                    }
                }
            }
        }
        else {
            while !feof(handle) {
                let row = fgets(handle);
                if strlen(row) {
                    let rows[] = json_decode(row);
                }
            }
        }
        if count(rows) {
            return rows;
        }
        return false;
    }

    public function done() -> bool|array {}
}