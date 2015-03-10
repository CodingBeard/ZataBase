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
    * Finished creating the query, check table for rows matching conditions and return the results
    * TODO: implement a resultset object
    */
    public function done() -> array|bool
    {
        var results;
        let results = this->getMatchedRows();
        if count(results) {
            return results;
        }
        return false;
    }
}