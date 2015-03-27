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

class Column {

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
    * Column's key
    * @var array
    */
    public key {
        set, get
    };

    /**
    * @var int
    */
    const INT_TYPE = 1;

    /**
    * @var int
    */
    const STRING_TYPE = 2;

    /**
    * @var int
    */
    const TEXT_TYPE = 3;

    /**
    * @var int
    */
    const DATE_TYPE = 4;

    /**
    * @var int
    */
    const DATETIME_TYPE = 5;

    /**
    * @var int
    */
    const PRIMARY_FLAG = 1;

    /**
    * @var int
    */
    const INCREMENT_FLAG = 2;

    /**
    * @var int
    */
    const UNIQUE_FLAG = 3;

    /**
    * Constructor
    * @param int type
    * @param array flags
    */
    public function __construct(const string! name, const int type, const array! flags = [], const int! key = -1)
    {
        let this->name = name;
        let this->type = type;
        let this->flags = flags;
        if key != -1 {
            let this->key = key;
        }
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

}