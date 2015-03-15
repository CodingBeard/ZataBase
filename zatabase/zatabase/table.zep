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
    public relations {
        set, get
    };

    /**
    * Constructor
    * @param string name
    * @param array columns
    * @param int increment
    * @param array relationships
    */
    public function __construct(const string! name, const array! columns = [], const array! relations = [], const var offset = false)
    {
        var column, relation;

        let this->columns = [];

        for column in columns {
            this->addColumn(column);
        }

        let this->name = name;

        let this->relations = [];

        if typeof relations == "array" {
            if count(relations) {
                for relation in relations {
                    this->addRelation(relation);
                }
            }
        }

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
        elseif typeof column == "object" {
            if column instanceof "Column" {
                column->setKey(columnCount);
                let columnObject = column;
            }
            elseif column instanceof "stdClass" {
                let columnObject = new Column(column->name, column->type, column->flags, columnCount);
            }
            else {
                throw new Exception("Column must be an: Array, stdClass, or instance of Column.");
            }
        }
        else {
            throw new Exception("Column must be an: Array, stdClass, or instance of Column.");
        }

        if isset(this->columns[columnObject->name]) {
            throw new Exception("A table may not have two columns with the same name.");
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
    * Add a relationship to the table
    * @param \ZataBase\Table\RelationInterface relation
    */
    public function addRelation(var relation)
    {
        var parent, type, relationArray;

        if typeof relation == "array" {
            let relationArray = relation;
            let type = relation["type"];
            let relation = new {type}(
                relationArray["parentTable"],
                relationArray["parentColumn"],
                relationArray["childColumn"],
                relationArray["childTable"]
            );
        }

        let parent = this->{"schema"}->getTable(relation->getParentTable());

        if !parent {
            throw new Exception("You cannot add a relation if the parent table is non-existent.");
        }

        if !parent->hasColumn(relation->getParentColumn()) {
            throw new Exception("You cannot add a relation if the parent table's column is non-existent.");
        }

        if !this->hasColumn(relation->getChildColumn()) {
            throw new Exception("You cannot add a relation to this table with a non-existent child column.");
        }

        if strlen(relation->getChildTable()) == 0 {
            relation->setChildTable(this->name);
        }

        let this->relations[] = relation;
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
            this->{"schema"}->setIncrement(this->name, this->incrementValue);
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

        if this->increment {
            this->{"schema"}->setIncrement(this->name, this->incrementValue);
        }

        this->file->append(substr(appendedRows, 0, -1));
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
                        results->addRowOffset(this->file->ftell() - strlen(line));
                    }
                }

                this->file->next();
            }
        }
        else {
            while this->file->valid() {
                results->addRowOffset(this->file->ftell());
                this->file->current();
                this->file->next();
            }
            array_pop(results->rows);
        }
        return results;
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
        return json_encode([this->name, this->columns, this->relations]);
    }

    /**
    * Refresh the file handler
    */
    public function refresh()
    {
        let this->file = new FileHandler(this->{"storage"}->path(this->{"config"}->tablesDir . this->name), "c+");
    }
}