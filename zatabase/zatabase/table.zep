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

use Zatabase\Execute\Results;
use ZataBase\Di\Injectable;
use ZataBase\Helper\FileHandler;
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
    * Table's file handler
    * @var \SplFileObject
    */
    public file {
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
    public function __construct(const string! name, const array! columns = [], int increment = 0, const array! relationships = [], const var offset = false)
    {
        var columnArray, column, columnCount = 0;

        for columnArray in columns {
            if get_class(columnArray) == "stdClass" {
                let column = new Column(columnArray->name, columnArray->type, columnArray->flags, columnCount);
            }
            else {
                columnArray->setKey(columnCount);
                let column = columnArray;
            }
            let this->columns[column->name] = column;
            let this->columnMap[] = column->name;
            let columnCount++;
        }

        let this->name = name;
        let this->increment = increment;
        let this->relationships = relationships;
        let this->offset = offset;
        this->refresh();
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

    public function selectRows(const var conditions = [])
    {
        var row, condition, results;
        bool match = true;

        let results = new Results(this);

        if count(conditions) {
            while this->file->valid() {

                let match = true;

                let row = json_decode(this->file->current());

                if typeof row == "array" {
                    for condition in conditions {
                        if !condition->matches(row) {
                            let match = false;
                        }
                    }

                    if match {
                        let results->rows[] = this->file->key();
                    }
                }

                this->file->next();
            }
        }
        else {
            let results->rows = range(0, this->file->count());
        }
        return results;
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
        this->file->append(json_encode(row));
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
        this->file->append(substr(appendedRows, 0, -1));
    }

    /**
    * Delete row(s)
    * @param int offset
    */
    public function deleteRows(const var offsets) -> void
    {
        this->file->delete(offsets);
    }

    /**
    * Delete all rows
    */
    public function deleteAllRows() -> void
    {
        this->file->ftruncate(0);
    }

    /**
    * Serialize self
    */
    public function __toString()
    {
        return serialize(this);
    }

    /**
    * Properties to serialize
    */
    public function __sleep()
    {
        return ["name", "columns", "columnMap", "increment", "relationships"];
    }

    /**
    * Refresh the file handler on wakeup
    */
    public function __wakeup()
    {
        this->refresh();
    }

    /**
    * Refresh the file handler
    */
    public function refresh()
    {
        let this->file = new FileHandler(this->{"storage"}->path(this->{"config"}->tablesDir . this->name), "c+");
    }
}