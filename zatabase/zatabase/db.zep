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
    * @var storage Storage\StorageInterface
    */
    protected storage;

    /**
    * Location of the auth file
    * @var authFile string
    */
    protected authFile = ".auth";

    /**
    * Constructor
    * @param array parameters
    */
    public function __construct(adapter, const array! parameters)
    {
        let this->storage = adapter;
    }

    public function getStorage() -> <Storage\StorageInterface>
    {
        return this->storage;
    }

}