/*
 * ZataBase\Traverser\Row
 *
 * @category 
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Traverser;

use ZataBase\Di\Injectable;

class Row extends Injectable {

    /**
    * id
    * @var array
    */
    protected id {
        set, get
    };

    /**
    * Table Name
    * @var string
    */
    protected table {
        set, get
    };

    /**
    * Column Map
    * @var array
    */
    protected columnMap {
        set, get
    };

    /**
    * Constructor
    * @param string table
    */
    public function __construct(const int! id, const string! table, const array! columns, const array! values)
    {
        var count = 0, column;
        let this->id = id;
        let this->table = table;
        let this->columnMap = columns;
        for count, column in columns {
            let this->{column} = values[count];
        }
    }

    /**
    * Delete a self from a table
    * @param string name
    */
    public function delete()
    {
        this->{"storage"}->removeLine(this->table, this->id + 1);
    }

}