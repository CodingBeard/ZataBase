/*
 * ZataBase\Row
 *
 * @category 
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Traverser;

class Row {

    /**
    * id
    * @var array
    */
    protected id {
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
    public function __construct(const int! id, const array! columns, const array! values)
    {
        var count = 0, column;
        let this->id = id;
        let this->columnMap = columns;
        for count, column in columns {
            let this->{column} = values[count];
        }
    }

}