/*
 * ZataBase\Traverser
 *
 * @category 
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase;

use ZataBase\Traverser\Exception;
use ZataBase\Traverser\Row;
use ZataBase\Di\Injectable;

class Traverser extends Injectable {

    /**
    * File handler
    * @var mixed
    */
    protected handle {
        set, get
    };

    /**
    * Table Name
    * @var string
    */
    protected table {
        get
    };

    /**
    * Column Map
    * @var array
    */
    protected columnMap {
        set, get
    };

    /**
    * Starting Byte of the data
    * @var array
    */
    protected dataStart {
        set, get
    };

    /**
    * Set the table we will be traversing
    * @param Storage\Adapter\File adapter
    */
    public function setTable(const string! name)
    {
        var columns;
        let this->table = name;
        let this->handle = this->{"storage"}->getHandle(name);
        let columns = fgets(this->handle);
        let this->dataStart = strlen(columns);
        let this->columnMap = json_decode(columns, true);
    }

    public function findRow(const var! column, const var value) -> array|bool
    {
        fseek(this->handle, this->dataStart);
        var json, key;
        int count = 0;
        let key = array_search(column, this->columnMap);
        let json = [];
        while !feof(this->handle) {
            let count++;
            let json = json_decode(fgets(this->handle), true);
            if json[key] == value {
                return new Row(count, this->table, this->columnMap, json);
            }
        }
        return false;
    }

}