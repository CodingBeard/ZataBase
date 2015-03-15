/*
 * relationinterface
 *
 * @category 
 * @package 
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Table;

class Relation
{

    /**
    * @var string
    */
    public type {
        get, set
    };

    /**
    * @var string
    */
    public parentTable {
        get, set
    };

    /**
    * @var string
    */
    public parentColumn {
        get, set
    };

    /**
    * @var string
    */
    public childTable {
        get, set
    };

    /**
    * @var string
    */
    public childColumn {
        get, set
    };


}