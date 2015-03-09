/*
 * ZataBase\Execute\QueryType
 *
 * @category 
 * @package 
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Execute;

use ZataBase\Di\Injectable;
use ZataBase\Table;

class QueryType extends Injectable {

    /**
    * Table
    * @var Table
    */
    protected table {
        set, get
    };

    /**
    * @param string tableName
    */
    public function __construct(<Table> table)
    {
        let this->table = table;
    }

}