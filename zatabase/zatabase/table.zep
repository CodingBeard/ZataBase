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
    public offset {
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
    public function __construct(const string! name, const array! columns = [], int increment = 0, const array! relationships = [], const int offset = 0)
    {
        var columnArray, column, columnCount = 0;
        let this->offset = offset;
        let this->name = name;
        for columnArray in columns {
            if typeof columnArray == "array" {
                let column = new Column(columnArray["name"], columnArray["type"], columnArray["flags"], columnCount);
            }
            else {
                columnArray->setKey(columnCount);
                let column = columnArray;
            }
            let this->columns[column->name] = column;
            let this->columnMap[] = column->name;
            if increment == 0 {
                if column->hasFlag(Column::INCREMENT_FLAG) {
                    let increment = 1;
                }
            }
        }
        let this->increment = increment;
        let this->relationships = relationships;
    }

    /**
    * Save Table
    * TODO: replace
    */
    public function save() -> void
    {

    }

    /**
    * Delete table
    */
    public function delete() -> void
    {
        this->{"execute"}->delete(this->{"config"}->definitionName)
            ->where("name")->equals(this->name)->done();

        this->{"storage"}->removeFile(this->{"config"}->tablesDir . this->name);
    }

    /**
    * get the file handler for this table
    */
    public function getHandle()
    {
        var handle;
        let handle = this->{"storage"}->getHandle(this->{"config"}->tablesDir . this->name);
        return handle;
    }

    /**
    * Check if this table has a certain column
    * @param string columnName
    */
    public function hasColumn(const string! columnName) -> int|bool
    {
        if isset(this->columns[columnName]) {
            return this->columns[columnName];
        }
        return false;
    }

    /**
    * Return the position of a column
    * @param string columnName
    */
    public function columnKey(const string! columnName) -> int|bool
    {
        return array_search(columnName, this->columnMap);
    }

    /**
    * Insert a row
    * @param int rowId
    */
    public function insertRow(const array! row) -> void
    {
        if count(row) != count(this->columnMap) {
            throw new Exception("Row should contain the same number of values as columns in the table: " . implode(", ", this->columnMap));
        }
        this->{"storage"}->appendLine(this->{"config"}->tablesDir . "/" . this->name, json_encode(row));
    }

    /**
    * Insert rows
    * @param int rowId
    */
    public function insertRows(const array! rows) -> void
    {
        var row, appendedRows = "";
        for row in rows {
            if count(row) != count(this->columnMap) {
                throw new Exception("Row should contain the same number of values as columns in the table: " . implode(", ", this->columnMap));
            }
            let appendedRows .= json_encode(row) . PHP_EOL;
        }
        this->{"storage"}->appendLine(this->{"config"}->tablesDir . "/" . this->name, substr(appendedRows, 0, -1));
    }

    /**
    * Delete a row
    * @param int offset
    */
    public function deleteRow(const int! offset) -> void
    {
        this->{"storage"}->removeLine(this->{"config"}->tablesDir . "/" . this->name, offset);
    }

    /**
    * Delete all rows
    */
    public function deleteAllRows() -> void
    {
        this->{"storage"}->removeFile(this->{"config"}->tablesDir . "/" . this->name);
    }

    /**
    * Serialize self
    */
    public function __toString()
    {
        return json_encode([this->name, this->columns, this->increment, this->relationships]);
    }

    /**
    * Array self
    */
    public function toArray() -> array
    {
        return [this->name, this->columns, this->increment, this->relationships];
    }
}