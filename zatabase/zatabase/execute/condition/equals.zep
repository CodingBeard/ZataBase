/*
 * ZataBase\Execute\Condition\Equals
 *
 * @category 
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Execute\Condition;

class Equals {

    /**
    * True by default, false if this condition is notted
    * @var bool
    */
    protected matches = true {
        set, get
    };

    /**
    * Column ID
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
    * @param string tableName
    */
    public function __construct(const bool! isNot, const <\Zatabase\Table\Column> column, const var value)
    {
        let this->matches = isNot ? false : true;
        let this->column = column;
        let this->value = value;
    }

    public function matches(const array! row) -> bool
    {
        if row[this->column->getKey()] == this->value {
            return this->matches;
        }
        return !this->matches;
    }
}