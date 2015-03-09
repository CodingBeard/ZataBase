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
    * Location of the auth file
    * @var string
    */
    protected authFile = ".auth";

    /**
    * Constructor
    * @param array parameters
    */
    public function __construct(const array! parameters)
    {
        var di;
        let di = new Di();
        di->set("storage", new File(parameters[0]), true);
        di->set("schema", new Schema(), true);
        di->set("traverser", new Traverser(), true);
        this->setDI(di);

        if !this->{"storage"}->isFile("tables") {
            this->{"storage"}->setFile("tables", json_encode(["name", "columns", "increment", "relationships"]));
        }
    }

}