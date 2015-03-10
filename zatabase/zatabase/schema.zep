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

class Schema extends Injectable {

    /**
    * Definition File handler
    * @var mixed
    */
    protected handle {
        set, get
    };

    /**
    * Construct
    * @param string
    */
    public function __construct(const string! definitionFile)
    {
        let this->handle = this->{"storage"}->getHandle(definitionFile);
    }

    /**
    * Refresh the definition file handler
    * @param string
    */
    public function refreshDefinition()
    {
        let this->handle = this->{"storage"}->getHandle(this->{"config"}->definitionFile);
    }

    /**
    * Create a table
    * @param Table table
    */
    public function createTable(<Table> table)
    {
        if !this->getTable(table->name) {
            this->{"execute"}->insert(this->{"config"}->definitionName)->values(table->toArray());
            this->refreshDefinition();
        }
        else {
            throw new Exception("Table: '" . table->name . "' already exists.");
        }
    }

    /**
    * Instance a table from the tables' file
    * @param string name
    */
    public function getTable(const string! name) -> <Table>|bool
    {
        var row = [];
        rewind(this->handle);
        let row = json_decode(fgets(this->handle), true);
        while !feof(this->handle) {
            if row[0] == name {
                return new table(row[0], row[1], row[2], row[3], ftell(this->handle));
            }
            let row = json_decode(fgets(this->handle), true);
        }
        return false;
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
            table->delete();
            this->refreshDefinition();
        }
    }

}