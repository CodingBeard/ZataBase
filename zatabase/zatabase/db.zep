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
class Db extends Injectable {

    /**
    * Constructor
    */
    public function __construct(const array! config)
    {
        var di, conf;
        let conf = json_decode(json_encode(config));
        let di = new Di();
        di->set("config", conf, true);
        di->set("storage", new File(conf->databaseDir), true);
        di->set("execute", new Execute(), true);
        di->set("schema", new Schema(), true);
        di->set("traverser", new Traverser(), true);
        this->setDI(di);

        if !this->{"storage"}->isFile(conf->schema->definitionFile) {
            this->{"storage"}->setFile(conf->schema->definitionFile, json_encode(["name", "columns", "increment", "relationships"]));
        }
    }

}