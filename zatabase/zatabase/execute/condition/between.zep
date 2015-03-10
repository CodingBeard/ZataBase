/*
 * ZataBase\Execute\Condition\Between
 *
 * @category 
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Execute\Condition;

use ZataBase\Table\Column;

class Between {

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
    * Lower Value to check against
    * @var mixed
    */
    protected lowerValue {
        set, get
    };

    /**
    * Value to check against
    * @var mixed
    */
    protected higherValue {
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
    public function __construct(const bool! isNot, const <\Zatabase\Table\Column> column, const var lowerValue, const var higherValue, const bool! equal)
    {
        let this->matches = isNot ? false : true;
        let this->column = column;
        let this->lowerValue = lowerValue;
        let this->higherValue = higherValue;
        let this->equal = equal;
    }

    public function matches(const array! row) -> bool
    {
        var value;
        if this->column->type == Column::DATE_TYPE {
            let value = strtotime(row[this->column->getKey()]);
            if this->equal {
                if (strtotime(this->lowerValue) <= value) && (value <= strtotime(this->higherValue)) {
                    return this->matches;
                }
            }
            else {
                if (strtotime(this->lowerValue) < value) && (value < strtotime(this->higherValue)) {
                    return this->matches;
                }
            }
        }
        else {
            let value = intval(row[this->column->getKey()]);
            if this->equal {
                if (intval(this->lowerValue) <= value) && (value <= intval(this->higherValue)) {
                    return this->matches;
                }
            }
            else {
                if (intval(this->lowerValue) < value) && (value < intval(this->higherValue)) {
                    return this->matches;
                }
            }
        }
        return !this->matches;
    }
}