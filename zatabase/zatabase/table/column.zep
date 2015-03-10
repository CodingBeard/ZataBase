/*
 * Column
 *
 * @category 
 * @package 
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Table;

use ZataBase\Di\Injectable;

class Column extends Injectable {

    /**
    * Column's name
    * @var string
    */
    public name {
        set, get
    };

    /**
    * Column's type
    * @var int
    */
    public type {
        set, get
    };

    /**
    * Column's flags
    * @var array
    */
    public flags {
        set, get
    };

    /**
    * @var int
    */
    const INT_TYPE = 0;

    /**
    * @var int
    */
    const STRING_TYPE = 1;

    /**
    * @var int
    */
    const TEXT_TYPE = 2;

    /**
    * @var int
    */
    const DATE_TYPE = 3;

    /**
    * @var int
    */
    const JSON_TYPE = 3;

    /**
    * @var int
    */
    const PRIMARY_FLAG = 0;

    /**
    * @var int
    */
    const INCREMENT_FLAG = 1;

    /**
    * @var int
    */
    const UNIQUE_FLAG = 2;

    /**
    * Constructor
    * @param int type
    * @param array flags
    */
    public function __construct(const string! name, const int! type, const array! flags = [])
    {
        let this->name = name;
        let this->type = type;
        let this->flags = flags;
    }

    /**
    * Check if this column has a flag type
    */
    public function hasFlag(const int flagID) -> bool
    {
        var flag;
        for flag in this->flags {
            if flag == flagID {
                return true;
            }
        }
        return false;
    }

    /**
    * Serialize self
    */
    public function __toString()
    {
        return json_encode([this->name, this->type, this->flags]);
    }

}