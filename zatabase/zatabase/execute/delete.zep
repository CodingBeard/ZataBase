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
        var results;
        if typeof this->conditions == "array" {
            let results = this->table->selectRows(this->conditions);
            if results->count() {
                this->table->deleteRows(results->rows);
            }
        }
        else {
            this->table->deleteAllRows();
        }
        return true;
    }
}