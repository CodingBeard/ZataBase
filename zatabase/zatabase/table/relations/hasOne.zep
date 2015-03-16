/*
 * ZataBase\Table\Relations\HasOne
 *
 * @category 
 * @package 
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Table\Relations;

use ZataBase\Table\Relation;

class HasOne extends Relation
{
    /**
    * Constructor
    *
    * @param string parentTable
    * @param string parentColumn
    * @param string childColumn
    */
    public function __construct(const string! parentTable, const string! parentColumn, const string! childColumn, const string! childTable = "")
    {
        let this->type = get_class(this);
        let this->parentTable = parentTable;
        let this->parentColumn = parentColumn;
        let this->childColumn = childColumn;

        if strlen(childTable) {
            let this->childTable = childTable;
        }
    }

}