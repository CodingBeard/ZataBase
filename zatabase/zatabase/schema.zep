/*
 * ZataBase\Schema
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase;

use ZataBase\Di\Injectable;
use ZataBase\Table;
use ZataBase\Table\Column;

class Schema extends Injectable {

    /**
    * File handlers
    * @var mixed
    */
    protected handlers {
        set, get
    };

    /**
    * Increments
    * @var mixed
    */
    protected increments {
        set, get
    };

    /**
    * Construct
    * @param string
    */
    public function __construct(const string! tablesDir)
    {
        let this->handlers["schema"] = this->{"storage"}->getHandle(tablesDir . "_schema");
        let this->handlers["increments"] = this->{"storage"}->getHandle(tablesDir . "_increments");

        if !this->getTable("_schema") {
            this->handlers["schema"]->append(new Table("_schema", [
                new Column("name", Column::STRING_TYPE),
                new Column("columns", Column::JSON_TYPE),
                new Column("relationships", Column::JSON_TYPE)
            ]));
        }
        if !this->getTable("_increments") {
            this->handlers["schema"]->append(new Table("_increments", [
                new Column("name", Column::STRING_TYPE),
                new Column("increment", Column::INT_TYPE)
            ]));
        }

        register_shutdown_function([this, "shutdown"]);
    }

    /**
    * Refresh the definition file handler
    * @param string
    */
    public function refresh()
    {
        let this->handlers["schema"] = this->{"storage"}->getHandle(this->{"config"}->tablesDir . "_schema");
        let this->handlers["increments"] = this->{"storage"}->getHandle(this->{"config"}->tablesDir . "_increments");
    }

    /**
    * Instance a table from the tables' file
    * @param string name
    */
    public function getTable(const string! name) -> <Table>|bool
    {
        var row, table;
        this->handlers["schema"]->rewind();
        while this->handlers["schema"]->valid() {
            let row = this->handlers["schema"]->current();
            let table = unserialize(row);
            if typeof table == "object" {
                if table->name == name {
                    table->setOffset(this->handlers["schema"]->ftell());
                    return table;
                }
            }
            this->handlers["schema"]->next();
        }
        return false;
    }

    /**
    * Create a table
    * @param Table table
    */
    public function createTable(<Table> table)
    {
        if !this->getTable(table->name) {
            this->handlers["schema"]->append(table);
            this->refresh();
        }
        else {
            throw new Exception("Table: '" . table->name . "' already exists.");
        }
    }

    /**
    * Delete a table
    * @param Table table
    */
    public function deleteTable(const string! name)
    {
        var table;
        let table = this->getTable(name);
        if table {
            this->handlers["schema"]->delete(table->offset);
            table->deleteAllRows();
            this->refresh();
        }
    }

    /**
    * Save a table
    * @param Table table
    */
    public function saveTable(<Table> table)
    {
        this->handlers["schema"]->replace(table->offset, table);
        this->refresh();
    }

    /**
    * Set a table increment value
    * @param string tableName
    * @param int increment
    */
    public function setIncrement(const string! tableName, const int incrementKey, const int incrementValue)
    {
        let this->increments[tableName] = [incrementKey, incrementValue];
    }

    /**
    * Get a table increment value
    * @param string tableName
    */
    public function getIncrement(const string! tableName)
    {
        var row, increment;
        let row = [];
        if typeof this->increments != "array" {
            this->handlers["increments"]->rewind();
            while this->handlers["increments"]->valid() {
                let row = json_decode(this->handlers["increments"]->current());
                if typeof row == "array" {
                    let this->increments[row[0]] = [row[1], row[2]];
                }
                this->handlers["increments"]->next();
            }
        }

        if fetch increment, this->increments[tableName] {
            return increment;
        }
        return 1;
    }

    public function shutdown()
    {
        var tableName, increment;
        if typeof this->increments == "array" {
            this->handlers["increments"]->ftruncate(0);
            for tableName, increment in this->increments {
                this->handlers["increments"]->append(json_encode([tableName, increment[0], increment[1]]));
            }
        }
    }

}