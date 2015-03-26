/*
 * btree
 *
 * @category 
 * @package 
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Storage;

use ZataBase\Di\Injectable;
use ZataBase\Storage\BTree\Node;
use ZataBase\Storage\BTree\Node\Element;

class BTree extends Injectable
{
    /**
    * B Tree index handler
    * @var \ZataBase\Helper\FileHandler
    */
    public index {
        set, get
    };

    /**
    * Data file handler
    * @var \ZataBase\Helper\FileHandler
    */
    public data {
        set, get
    };

    /**
    * Number of children
    */
    public children = 4 {
        set, get
    };

    /**
    * Whether the index is unique
    */
    public unique = true {
        set, get
    };

    /**
    * @param string indexPath
    * @param string dataPath
    */
    public function __construct(const string! indexPath, const string! dataPath)
    {
        let this->index = this->{"storage"}->getHandle(indexPath);
        let this->data = this->{"storage"}->getHandle(dataPath);
    }

    /**
    * Return the data which corresponds to the key
    * @param string|int key
    */
    public function find(const var key) -> array|bool
    {
        var element;

        this->index->fseek(0);
        if empty(this->index->fgetcsv()) {
            return false;
        }

        let element = this->locate(0, key);

        if element {
            this->data->fseek(element->getByte());
            return this->data->fgetcsv();
        }
        return false;
    }

    /**
    * Locate a node and search for the key's element
    * @param string|int key
    */
    protected function locate(const int byte, const var key) -> <\ZataBase\Storage\BTree\Node\Element>|bool
    {
        var csv, node, element;

        this->index->fseek(byte);

        let csv = this->index->fgetcsv();

        if empty(csv) {
            return false;
        }

        this->index->fseek(byte);

        let node = Node::load(this->index);

        if node {
            let element = node->hasKey(key);

            if typeof element == "object" {
                return element;
            }
            elseif typeof element == "int" {
                return self::locate(element, key);
            }
        }
        return false;
    }

    /**
    * Add an index to the tree, array of [key, byteLocation]
    * @param array index
    */
    public function insert(const array! index)
    {
        if this->locate(0, index[0]) {
            throw new Exception("The provided Key: '" . index[0] . "' must be unique.");
        }

    }

}