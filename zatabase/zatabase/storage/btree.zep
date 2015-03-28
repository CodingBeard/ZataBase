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
    public elementCount = 4 {
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
    * How many bytes each node is
    * @var int
    */
    public nodeLength {
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
            let this->nodeLength = 5 * (85 + strlen(PHP_EOL));
        }
        else {
            let this->keyType = root->getFirst()->getKeyType();
            this->index->fseek(0);
            let this->nodeLength = 5 * strlen(this->index->current());
            this->index->fseek(0);
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
    * Locate a node and potentially search for the key's element
    * @param int nodeNumber
    * @param string|int key
    * @param array path
    */
    protected function locate(const int nodeNumber, const var key = false, array path = []) -> bool|<\ZataBase\Storage\BTree\Node>|<\ZataBase\Storage\BTree\Node\Element>
    {
        var csv, node, element, byte;

        let byte = nodeNumber * this->nodeLength;

        let csv = this->index->getcsv(byte);

        if empty(csv) {
            return false;
        }

        this->index->fseek(byte);

        let node = Node::load(this->index);

        if node {

            if empty(path) {
                let path[] = nodeNumber;
            }

            if key === false {
                if typeof node->getPath() == "array" {
                    node->setParentId(end(node->getPath()));
                }
                node->setPath(path);
                return node;
            }

            let element = node->hasKey(key);

            if typeof element == "object" {
                return element;
            }
            elseif typeof element == "int" {
                let path[] = element;
                return this->locate(element, key, path);
            }
            else {
                if typeof node->getPath() == "array" {
                    node->setParentId(end(node->getPath()));
                }
                node->setPath(path);
                return node;
            }
        }
        return false;
    }

    /**
    * Get the parent of a node, or false if it does not have one
    * @param \ZataBase\Storage\BTree\Node node
    */
    protected function getParent(<\ZataBase\Storage\BTree\Node> node) -> <\ZataBase\Storage\BTree\Node>|bool
    {
        var path;

        let path = node->path;
        if count(path) > 1 {
            end(path);
            return this->locate(prev(path));
        }
        return false;
    }

    /**
    * Insert a row to the data file and add an index pointer
    * @param array index
    */
    public function insert(const array! row, const var key = false)
    {
        var location;

        let location = this->data->length();
        this->data->appendcsv(row);

        if key === false {
            if this->keyType != Element::KEY_INT {
                throw new Exception("A key must be provided if the key type is not an int.");
            }

        }
        else {
            this->insertIndex([key, location]);
        }
    }

    /**
    * Add an index to the tree, array of [key, byteLocation]
    * @param array index
    */
    public function insertIndex(const array! index)
    {
        var result, newNode;

        let result = this->locate(0, index[0]);

        if !result {
            let newNode = new Node([
                new Element(Element::KEY_DETECT, index[0], index[1])
            ]);
            return this->index->appendRaw(newNode->toString(this->elementCount));
        }
        elseif typeof result == "object" {
            if result instanceof "\ZataBase\Storage\BTree\Node\Element" {
                throw new Exception("The provided Key: '" . index[0] . "' must be unique.");
            }
        }

        if result->count() < this->elementCount {
            result->addElement(new Element(Element::KEY_DETECT, index[0], index[1]));
            this->index->fseek(result->getId() * this->nodeLength);
            this->index->fwrite(result->toString(this->elementCount));
        }
        else {
            this->split(result, new Element(Element::KEY_DETECT, index[0], index[1]));
        }
    }

    /**
    * Add an element which splits a node
    * @param \ZataBase\Storage\BTree\Node node
    * @param \ZataBase\Storage\BTree\Node\Element element
    */
    public function split(<\ZataBase\Storage\BTree\Node> node, <\ZataBase\Storage\BTree\Node\Element> element)
    {
        var parent, left, right, rightByteLocation, median;

        node->addElement(element);

        let median = node->elements[ceil(count(node->elements) / 2) - 1];

        unset(node->elements[ceil(count(node->elements) / 2) - 1]);

        let right = array_slice(node->elements, ceil(count(node->elements) / 2));

        let left = array_slice(node->elements, 0, ceil(count(node->elements) / 2) - 1);

        node->setElements(left);

        this->index->fseek(node->getId() * this->nodeLength);
        this->index->fwrite(node->toString(this->elementCount), this->nodeLength - strlen(PHP_EOL));

        median->setLess(node->getId());

        let rightByteLocation = this->index->appendRaw(new Node(right)->toString(this->elementCount));

        median->setMore(rightByteLocation / this->nodeLength);

        let parent = this->getParent(node);

        if parent {
            if parent->count() < this->elementCount {
                parent->addElement(median);
                this->index->fseek(parent->getId() * this->nodeLength);
                this->index->fwrite(parent->toString(this->elementCount), this->nodeLength - strlen(PHP_EOL));
            }
            else {
                this->split(parent, median);
            }
        }
        else {
            //deal with root
        }
    }

}