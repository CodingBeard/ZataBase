/*
 * ZataBase\Execute\Delete
 *
 * @category 
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Execute;

class Delete extends Condition
{
    /**
    * Finished creating the query, check table for rows matching conditions
    */
    public function done() -> <\ZataBase\Execute\Results>|bool
    {
        var offset, results;
        if typeof this->conditions == "array" {
            let results = this->getMatchedRows();
            if results->count() {
                for offset in results->rows {
                    this->table->deleteRow(offset);
                }
            }
        }
        else {
            this->table->deleteAllRows();
        }
        return true;
    }
}