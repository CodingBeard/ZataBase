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
use ZataBase\Schema\Alter;
use ZataBase\Table;
use ZataBase\Table\Column;

class Schema extends Injectable {

    /**
    * Tables
    * @var array
    */
    protected tables {
        set, get
    };

    /**
    * increment
    * @var mixed
    */
    protected increment {
        set, get
    };

    /**
    * Construct
    * @param string
    */
    public function __construct()
    {
        this->refresh();

        register_shutdown_function([this, "save"]);
    }

    /**
    * Refresh the table definitions
    */
    public function refresh()
    {
        var file;

        let this->tables = [];

        for file in this->{"storage"}->scanDir() {
            this->getTable(file);
        }
    }

    /**
    * Check for a table
    * @param string name
    */
    public function getTable(const string! name) -> <Table>|bool
    {
        var columns, relations, increment;
        if this->{"storage"}->isFile(name . "/.zatabasetable") {

            if this->{"storage"}->isFile(name . "/columns") {
                let columns = unserialize(this->{"storage"}->getFile(name . "/columns"));
            }
            else {
                let columns = [];
            }

            if this->{"storage"}->isFile(name . "/relations") {
                let relations = unserialize(this->{"storage"}->getFile(name . "/relations"));
            }
            else {
                let relations = [];
            }

            if this->{"storage"}->isFile(name . "/increment") {
                let increment = abs(this->{"storage"}->getFile(name . "/increment"));
            }
            else {
                let increment = false;
            }

            let this->tables[name] = new Table(name, columns, relations, increment);
            return this->tables[name];
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
            this->{"storage"}->addDir(table->name);
            this->{"storage"}->setFile(table->name . "/columns", serialize(table->columns));
            this->{"storage"}->setFile(table->name . "/relations", serialize(table->relations));
            this->{"storage"}->setFile(table->name . "/increment", serialize(table->increment));
            this->{"storage"}->touch(table->name . "/.zatabasetable");
            this->save();
            this->refresh();
        }
        else {
            throw new Exception("Table: '" . table->name . "' already exists.");
        }
    }

    /**
    * Delete a table
    * @param string name
    */
    public function deleteTable(const string! name)
    {
        var table;
        let table = this->getTable(name);
        if table {
            this->{"storage"}->removeDir(table->name);
            this->save();
            this->refresh();
        }
    }

    /**
    * Delete a table
    * @param string name
    */
    public function alterTable(const string! name)
    {
        var table, alter;
        let table = this->getTable(name);
        if table {
            let alter = new Alter(table);
            alter->setDI(this->getDI());
            return alter;
        }
    }

    /**
    * Save a table
    * @param Table table
    */
    public function saveTable(<Table> table)
    {
        let this->tables[table->name] = table;
        this->save();
        this->refresh();
    }

    public function save()
    {
        var table;
        if typeof this->tables == "array" {
            for table in this->tables {
                if this->{"storage"}->isDir(table->name) {
                    this->{"storage"}->setFile(table->name . "/columns", serialize(table->columns));
                    this->{"storage"}->setFile(table->name . "/relations", serialize(table->relations));
                    this->{"storage"}->setFile(table->name . "/increment", serialize(table->increment));
                }
            }
        }
    }

}