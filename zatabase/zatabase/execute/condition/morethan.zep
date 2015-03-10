/*
 * ZataBase\Execute\Condition\MoreThan
 *
 * @category 
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Execute\Condition;

use ZataBase\Table\Column;

class MoreThan {

    /**
    * True by default, false if this condition is notted
    * @var bool
    */
    protected matches = true {
        set, get
    };

    /**
    * Column
    * @var int
    */
    protected column {
        set, get
    };

    /**
    * Value to check against
    * @var mixed
    */
    protected value {
        set, get
    };

    /**
    * Whether to check for >=
    * @var mixed
    */
    protected equal {
        set, get
    };

    /**
    * @param string tableName
    */
    public function __construct(const bool! isNot, const <\Zatabase\Table\Column> column, const var value, const bool! equal)
    {
        let this->matches = isNot ? false : true;
        let this->column = column;
        let this->value = value;
        let this->equal = equal;
    }

    public function matches(const array! row) -> bool
    {
        if this->equal {
            if this->column->type == Column::DATE_TYPE {
                if strtotime(row[this->column->getKey()]) >= strtotime(this->value) {
                    return this->matches;
                }
            }
            else {
                if intval(row[this->column->getKey()]) >= intval(this->value) {
                    return this->matches;
                }
            }
        }
        else {
            if this->column->type == Column::DATE_TYPE {
                if strtotime(row[this->column->getKey()]) > strtotime(this->value) {
                    return this->matches;
                }
            }
            else {
                if intval(row[this->column->getKey()]) > intval(this->value) {
                    return this->matches;
                }
            }
        }
        return !this->matches;
    }
}