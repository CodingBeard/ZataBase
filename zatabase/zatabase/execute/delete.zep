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
    public function done() -> array|bool
    {
        var handle, row, condition, count = 0;
        let handle = this->table->getHandle();
        if count(this->conditions) {
            while !feof(handle) {
                let count++;
                let row = json_decode(fgets(handle));
                for condition in this->conditions {
                    if condition->matches(row) {
                        this->table->deleteRow(count);
                    }
                }
            }
        }
        else {
            this->table->deleteAllRows();
        }
        return false;
    }
}