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

use ZataBase\Di;
use ZataBase\Di\Injectable;
use ZataBase\Storage\Adapter\File;

/**
* ZataBase\db
* $db = new ZataBase\db([__DIR__ . '/database']);
*/
class Db extends Injectable
{
    /**
    * Constructor
    */
    public function __construct(const <\ZataBase\Helper\ArrayToObject> config)
    {
        var di;

        let di = new Di();
        di->set("config", config, true);
        di->set("storage", new File(config->{"databaseDir"}), true);
        di->set("schema", new Schema(), true);
        di->set("execute", new Execute(), true);
        this->setDI(di);
    }

    /**
    * Alias of Schema::createTable
    *
    * @param Table table
    */
    public function createTable(const <\ZataBase\Table> table) -> void
    {
        this->{"schema"}->createTable(table);
    }

    /**
    * Alias of Schema::deleteTable
    *
    * @param string tableName
    */
    public function deleteTable(const string! tableName) -> void
    {
        this->{"schema"}->deleteTable(tableName);
    }

    /**
    * Alias of Schema::alterTable
    *
    * @param string tableName
    */
    public function alterTable(const string! tableName) -> <\ZataBase\Schema\Alter>
    {
        return this->{"schema"}->alterTable(tableName);
    }

    /**
    * Alias of Execute::insert
    *
    * @param string tableName
    */
    public function insert(const string! tableName) -> <\ZataBase\Execute\Insert>
    {
        return this->{"execute"}->insert(tableName);
    }

    /**
    * Alias of Execute::select
    *
    * @param array parameters
    */
    public function select(const string! tableName) -> <\ZataBase\Execute\Select>
    {
        return this->{"execute"}->select(tableName);
    }

    /**
    * Alias of Execute::delete
    *
    * @param array parameters
    */
    public function delete(const string! tableName) -> <\ZataBase\Execute\Select>
    {
        return this->{"execute"}->delete(tableName);
    }

    /**
    * Alias of Execute::update
    *
    * @param array parameters
    */
    public function update(const string! tableName) -> <\ZataBase\Execute\Update>
    {
        return this->{"execute"}->update(tableName);
    }

}