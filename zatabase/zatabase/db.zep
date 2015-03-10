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
use ZataBase\Table;
use ZataBase\Table\Column;

/**
* ZataBase\db
* $db = new ZataBase\db([__DIR__ . '/database']);
*/
class Db extends Injectable {

    /**
    * Constructor
    */
    public function __construct(const var! config)
    {
        var di, storage;
        let storage = new File(config->databaseDir);
        let config->definitionFile = config->tablesDir . config->definitionName;

        if !storage->isFile(config->definitionFile) {
            storage->setFile(config->definitionFile, new Table(config->definitionName, [
                new Column("name", Column::STRING_TYPE),
                new Column("columns", Column::JSON_TYPE),
                new Column("increment", Column::INT_TYPE),
                new Column("relationships", Column::JSON_TYPE)
            ]));
        }

        let di = new Di();
        di->set("config", config, true);
        di->set("storage", storage, true);
        di->set("execute", new Execute(), true);
        di->set("schema", new Schema(config->definitionFile), true);
        di->set("traverser", new Traverser(), true);
        this->setDI(di);
    }

}