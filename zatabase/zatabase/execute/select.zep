/*
 * ZataBase\Execute\Select
 *
 * @category 
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Execute;

class Select extends Condition
{

    /**
    * Finished creating the query, check table for rows matching conditions
    * TODO: implement a resultset object
    */
    public function done() -> array|bool
    {
        var handle, row, condition, results;
        let results = [];
        let handle = this->table->getHandle();
        if count(this->conditions) {
            while !feof(handle) {
                let row = json_decode(fgets(handle));
                for condition in this->conditions {
                    if condition->matches(row) {
                        let results[] = row;
                    }
                }
            }
        }
        else {
            while !feof(handle) {
                let row = fgets(handle);
                if strlen(row) {
                    let results[] = json_decode(row);
                }
            }
        }
        if count(results) {
            return results;
        }
        return false;
    }
}