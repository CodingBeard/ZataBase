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
    * Column ID
    * @var int
    */
    protected columnId {
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
    public function __construct(const int columnId, const var value)
    {
        let this->columnId = columnId;
        let this->value = value;
    }

    public function matches(const array! row) -> bool
    {
        if row[this->columnId] == this->value {
            return true;
        }
        return false;
    }
}