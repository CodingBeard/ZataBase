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
    * True/false
    * @var int
    */
    public increment = false {
        set, get
    };

    /**
    * Column with an auto incrementing value
    * @var int
    */
    public incrementKey {
        set, get
    };

    /**
    * Current increment
    * @var int
    */
    public incrementValue {
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
    public function __construct(const string! name, const array! columns = [], const array! relationships = [], const var offset = false)
    {
        var column;

        let this->columns = [];

        for column in columns {
            this->addColumn(column);
        }

        let this->name = name;
        let this->relationships = relationships;
        this->refresh();
    }

    /**
    * Check if this table has a certain column
    * @param string columnName
    */
    public function addColumn(const var column) -> void
    {
        var columnObject, columnCount;

        let columnCount = count(this->columns);

        if typeof column == "array" {
            let columnObject = new Column(column["name"], column["type"], column["flags"], columnCount);
        }
        else {
            column->setKey(columnCount);
            let columnObject = column;
        }

        if columnObject->hasFlag(Column::INCREMENT_FLAG) {
            if this->increment {
                throw new Exception("A table may only have one auto-incrementing value.");
            }
            let this->increment = true;
            let this->incrementKey = columnCount;
            let this->incrementValue = this->{"schema"}->getIncrement(this->name);
        }

        let this->columns[columnObject->name] = columnObject;
        let this->columnMap[] = columnObject->name;
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
    * Select rows from this table that match conditions,
    * If no conditions are given, all rows are selected
    * @param array conditions
    */
    public function selectRows(const var conditions = [])
    {
        var line, row, condition, results;
        bool match = true;

        this->file->rewind();

        let results = new Results(this);

        if count(conditions) {
            while this->file->valid() {

                let match = true;

                let line = this->file->current();
                let row = json_decode(line);

                if typeof row == "array" {
                    for condition in conditions {
                        if !condition->matches(row) {
                            let match = false;
                        }
                    }

                    if match {
                        let results->rows[] = this->file->ftell() - strlen(line);
                    }
                }

                this->file->next();
            }
        }
        else {
            while this->file->valid() {
                let results->rows[] = this->file->ftell();
                this->file->current();
                this->file->next();
            }
            array_pop(results->rows);
        }
        return results;
    }

    /**
    * Insert a row
    * @param int rowId
    */
    public function insertRow(array! row) -> void
    {
        if count(row) != count(this->columnMap) {
            throw new Exception("Row should contain the same number of values as columns in the table: " . implode(", ", this->columnMap));
        }

        if this->increment {
            if is_null(row[this->incrementKey]) {
                let row[this->incrementKey] = this->incrementValue;
                let this->incrementValue++;
            }
            else {
                let this->incrementValue = row[this->incrementKey];
            }
        }

        this->file->append(json_encode(row));
        this->{"schema"}->setIncrement(this->name, this->incrementKey, this->incrementValue);
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

            if this->increment {
                if is_null(row[this->incrementKey]) {
                    let row[this->incrementKey] = this->incrementValue;
                    let this->incrementValue++;
                }
                else {
                    let this->incrementValue = row[this->incrementKey];
                }
            }
            let appendedRows .= json_encode(row) . PHP_EOL;
        }

        this->file->append(substr(appendedRows, 0, -1));
        this->{"schema"}->setIncrement(this->name, this->incrementKey, this->incrementValue);
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
        return json_encode([this->name, this->columns, this->relationships]);
    }

    /**
    * Refresh the file handler
    */
    public function refresh()
    {
        let this->file = new FileHandler(this->{"storage"}->path(this->{"config"}->tablesDir . this->name), "c+");
    }
}