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
use ZataBase\Di\InjectionAwareInterface;
use ZataBase\Helper\FileHandler;
use ZataBase\Table\Column;

class Table extends Injectable
{
    /**
    * Table's file handler
    * @var \ZataBase\Helper\FileHandler
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
    * false or array of [increment column key, increment value]
    * @var int
    */
    public increment = false {
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
    public function __construct(const string! name, const array! columns = [], const array! relations = [], const var increment = false)
    {
        var column, relation;

        let this->name = name;
        let this->increment = increment;

        let this->columns = [];

        if typeof columns == "array" {
            if count(columns) {
                for column in columns {
                    if typeof column != "object" {
                        throw new Exception("Column must be an instance of ZataBase\\Table\\Column.");
                    }
                    if !(column instanceof \ZataBase\Table\Column) {
                        throw new Exception("Column must be an instance of ZataBase\\Table\\Column.");
                    }
                    this->addColumn(column);
                }
            }
        }

        let this->relations = [];

        if typeof relations == "array" {
            if count(relations) {
                for relation in relations {
                    if typeof relation != "object" {
                        throw new Exception("relation must be an instance of ZataBase\\Table\\Relation.");
                    }
                    if !(relation instanceof \ZataBase\Table\Relation) {
                        throw new Exception("relation must be an instance of ZataBase\\Table\\Relation.");
                    }
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
    public function addColumn(<\ZataBase\Table\Column> column) -> void
    {
        var columnCount;

        let columnCount = count(this->columns);

        column->setKey(columnCount);

        if isset(this->columns[column->name]) {
            throw new Exception("A table may not have two columns with the same name.");
        }

        if column->hasFlag(Column::INCREMENT_FLAG) {
            if typeof this->increment == "array" {
                throw new Exception("A table may only have one auto-incrementing value.");
            }
            let this->increment = ["key": columnCount, "value": 1];
        }

        let this->columns[column->name] = column;
        let this->columnMap[] = column->name;
    }

    /**
    * Add a relationship to the table
    * @param \ZataBase\Table\Relation relation
    */
    public function addRelation(<\ZataBase\Table\Relation> relation)
    {
        /* TODO: optional checking
        var parent;

        let parent = this->{"schema"}->getTable(relation->getParentTable());

        if !parent {
            throw new Exception("You cannot add a relation if the parent table is non-existent.");
        }

        if !parent->hasColumn(relation->getParentColumn()) {
            throw new Exception("You cannot add a relation if the parent table's column is non-existent.");
        }
        */

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
            if is_null(row[this->increment["key"]]) {
                let row[this->increment["key"]] = this->increment["value"];
                let this->increment["value"] = this->increment["value"] + 1;
            }
            else {
                let this->increment["value"] = row[this->increment["key"]];
            }
        }

        this->file->appendcsv(row);
    }

    /**
    * Insert rows
    * @param int rowId
    */
    public function insertRows(const array! rows) -> void
    {
        var row, updatedRows;
        let updatedRows = [];
        for row in rows {
            if count(row) != count(this->columnMap) {
                throw new Exception("Row should contain the same number of values as columns in the table: " . implode(", ", this->columnMap));
            }

            if this->increment {
                if is_null(row[this->increment["key"]]) {
                    let row[this->increment["key"]] = this->increment["value"];
                    let this->increment["value"] = this->increment["value"] + 1;
                }
                else {
                    let this->increment["value"] = row[this->increment["key"]];
                }
            }
            let updatedRows[] = row;
        }

        this->file->appendcsvs(updatedRows);
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
                let row = str_getcsv(line);

                if typeof row == "array" && strlen(line) {
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
    * Refresh/create the file handler
    */
    public function refresh()
    {
        this->{"storage"}->addDir(this->name);
        let this->file = new FileHandler(this->{"storage"}->path(this->name . "/data"), "c+");
    }
}