/*
 * ZataBase\Execute\Condition\Within
 *
 * @category 
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Execute\Condition;

class Within {

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
    * Values to check against
    * @var mixed
    */
    protected values {
        set, get
    };

    /**
    * @param string tableName
    */
    public function __construct(const bool! isNot, const <\Zatabase\Table\Column> column, const array! values)
    {
        let this->matches = isNot ? false : true;
        let this->column = column;
        let this->values = values;
    }

    public function matches(const array! row) -> bool
    {
        var value;
        for value in this->values {
            if row[this->column->getKey()] == value {
                return this->matches;
            }
        }
        return !this->matches;
    }
}