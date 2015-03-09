/*
 * Table
 *
 * @category 
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase;

use ZataBase\Di\Injectable;
use ZataBase\Table\Column;

class Table extends Injectable {

    /**
    * Table's name
    * @var string
    */
    public name = "";

    /**
    * Table's columns
    * @var array
    */
    public columns = [];

    /**
    * Table's increment counter
    * @var int
    */
    public increment = 0;

    /**
    * Table's relationships
    * @var array
    */
    public relationships = [];

    /**
    * Constructor
    * @param string name
    * @param array columns
    * @param int increment
    * @param array relationships
    */
    public function __construct(const string! name, const array! columns = [], const int! increment = 0, const array! relationships = [])
    {
        var column;
        let this->name = name;
        for column in columns {
            if is_array(column) {
                let this->columns[] = new Column(column["name"], column["type"], column["flags"]);
            }
            let this->columns[] = column;
        }
        let this->increment = increment;
        let this->relationships = relationships;
    }

    /**
    * Serialize self
    */
    public function __toString()
    {
        return json_encode([this->name, this->columns, this->increment, this->relationships]);
    }
}