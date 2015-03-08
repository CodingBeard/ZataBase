/*
 * Database
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase;

/**
* ZataBase\db
* $db = new ZataBase\db(new Zatabase\Storage\File(__DIR__ . '/database'), []);
*/
class Db {

    /**
    * Storage adapter
    * @var Storage\StorageInterface
    */
    protected storage {
        set, get
    };

    /**
    * Traverser
    * @var Traverser
    */
    protected traverser {
        set, get
    };

    /**
    * Location of the auth file
    * @var string
    */
    protected authFile = ".auth";

    /**
    * Constructor
    * @param Storage\File adapter
    * @param array parameters
    */
    public function __construct(<Storage\Adapter\File> adapter, const array! parameters)
    {
        let this->storage = adapter;
        if !this->storage->isFile("tables") {
            this->storage->setFile("tables", json_encode(["name", "columns", "increment", "relationships"]));
        }
        let this->traverser = new Traverser(this->storage);
    }

    /**
    * Create a table
    */
    public function createTable(<Table> table)
    {
        if !this->getTable(table->name) {
            this->storage->appendLine("tables", table);
        }
        else {
            throw new Exception("Table: '" . table->name . "' already exists.");
        }
    }

    /**
    * Instance a table from the tables' file
    */
    public function getTable(const string! name)
    {
        var row;
        this->traverser->setTable("tables");
        let row = this->traverser->findRow("name", name);
        if row {
            return new table(row->name, row->columns, row->increment, row->relationships);
        }
        else {
            return false;
        }
    }

}