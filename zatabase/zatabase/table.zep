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
    * Table's id in the schema
    * @var string
    */
    public id {
        set, get
    };

    /**
    * Table's name
    * @var string
    */
    public name {
        set, get
    };

    /**
    * Table's columns
    * @var array
    */
    public columns {
        set, get
    };

    /**
    * Table's column map
    * @var array
    */
    public columnMap {
        set, get
    };

    /**
    * Table's increment counter
    * @var int
    */
    public increment {
        set, get
    };

    /**
    * Table's relationships
    * @var array
    */
    public relationships {
        set, get
    };

    /**
    * Constructor
    * @param string name
    * @param array columns
    * @param int increment
    * @param array relationships
    */
    public function __construct(const string! name, const array! columns = [], const int! increment = 0, const array! relationships = [], const int id = 0)
    {
        var column;
        let this->id = id;
        let this->name = name;
        for column in columns {
            if typeof column == "array" {
                let this->columns[] = new Column(column["name"], column["type"], column["flags"]);
                let this->columnMap[] = column["name"];
            }
            else {
                let this->columns[] = column;
                let this->columnMap[] = column->name;
            }
        }
        let this->increment = increment;
        let this->relationships = relationships;
    }

    /**
    * Create table
    */
    public function create() -> void
    {
        this->{"storage"}->appendLine(this->{"config"}->schema->definitionFile, this);
        this->{"storage"}->setFile(this->{"config"}->schema->tablesDir . "/" . this->name, json_encode(this->getColumnMap()));
    }

    /**
    * Delete table
    */
    public function delete() -> void
    {
        this->{"storage"}->removeLine(this->{"config"}->schema->definitionFile, this->id + 1);
        this->{"storage"}->removeFile(this->{"config"}->schema->tablesDir . this->name);
    }

    /**
    * Check if this table has a certain column
    */
    public function hasColumn(const string! columnName) -> int|bool
    {
        return array_search(columnName, this->columnMap);
    }

    /**
    * get the file handler for this table
    */
    public function getHandle()
    {
        return this->{"storage"}->getHandle(this->{"config"}->schema->tablesDir . "/" . this->name);
    }

    /**
    * Serialize self
    */
    public function __toString()
    {
        return json_encode([this->name, this->columns, this->increment, this->relationships]);
    }
}