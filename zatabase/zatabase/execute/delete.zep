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
    public function done() -> bool
    {
        var row, rows;
        if typeof this->conditions == "array" {
            let rows = this->getMatchedRows();
            if count(rows) {
                for row in rows {
                    this->table->deleteRow(row);
                }
            }
        }
        else {
            this->table->deleteAllRows();
        }
        return true;
    }
}