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
    * Max number of elements
    * @var int
    */
    public elements = 4 {
        set, get
    };

    /**
    * Whether the index is unique
    * @var int
    */
    public unique = true {
        set, get
    };

    /**
    * What the type of our key is
    * @var int
    */
    public keyType {
        get, set
    };

    /**
    * @param string indexPath
    * @param string dataPath
    */
    public function __construct(const string! indexPath, const string! dataPath)
    {
        var root;
        let this->index = this->{"storage"}->getHandle(indexPath);
        let this->data = this->{"storage"}->getHandle(dataPath);

        let root = Node::load(this->index);

        if !root {
            let this->keyType = Element::KEY_DETECT;
        }
        else {
            let this->keyType = root->getFirst()->getKeyType();
        }
    }

    /**
    * Return the data which corresponds to the key
    * @param string|int key
    */
    public function find(const var key) -> array|bool
    {
        var result;

        if empty(this->index->getcsv(0)) {
            return false;
        }

        let result = this->locate(0, key);

        if typeof result == "object" {
            if result instanceof "\ZataBase\Storage\BTree\Node\Element" {
                return this->data->getcsv(result->getByte());
            }
        }
        return result;
    }

    /**
    * Locate a node and search for the key's element
    * @param string|int key
    */
    protected function locate(const int byte, const var key) -> bool|<\ZataBase\Storage\BTree\Node>|<\ZataBase\Storage\BTree\Node\Element>
    {
        var csv, node, element;

        let csv = this->index->getcsv(byte);

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
            else {
                return node;
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
        var result, newNode;

        let result = this->locate(0, index[0]);

        if !result {
            let newNode = new Node([
                new Element(Element::KEY_DETECT, index[0], index[1])
            ]);
            return this->index->appendRaw(newNode->toString());
        }
        elseif typeof result == "object" {
            if result instanceof "\ZataBase\Storage\BTree\Node\Element" {
                throw new Exception("The provided Key: '" . index[0] . "' must be unique.");
            }
        }

        if result->count() < this->elements {
            result->addElement(new Element(Element::KEY_DETECT, index[0], index[1]));
        }
        else {

        }
    }

}