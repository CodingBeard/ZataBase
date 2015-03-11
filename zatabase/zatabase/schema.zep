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
    * Definition File handler
    * @var mixed
    */
    protected file {
        set, get
    };

    /**
    * Construct
    * @param string
    */
    public function __construct(const string! definitionFile)
    {
        let this->file = this->{"storage"}->getHandle(definitionFile);
        if !this->getTable("Schema") {
            this->file->append(new Table("Schema", [
                new Column("name", Column::STRING_TYPE),
                new Column("columns", Column::JSON_TYPE),
                new Column("increment", Column::INT_TYPE),
                new Column("relationships", Column::JSON_TYPE)
            ]));
        }
    }

    /**
    * Refresh the definition file handler
    * @param string
    */
    public function refresh()
    {
        let this->file = this->{"storage"}->getHandle(this->{"config"}->definitionFile);
    }

    /**
    * Instance a table from the tables' file
    * @param string name
    */
    public function getTable(const string! name) -> <Table>|bool
    {
        var row, table;
        this->file->rewind();
        while this->file->valid() {
            let row = this->file->current();
            let table = unserialize(row);
            if typeof table == "object" {
                if table->name == name {
                    table->setOffset(this->file->key());
                    return table;
                }
            }
            this->file->next();
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
            this->file->append(table);
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
            this->file->delete(table->offset);
            this->refresh();
        }
    }

}