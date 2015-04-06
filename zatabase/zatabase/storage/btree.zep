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
use ZataBase\Helper\Csv;
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
            let this->nodeLength = (this->elementCount + 1) * (85 + strlen(PHP_EOL));
        }
        else {
            let this->keyType = root->getFirst()->getKeyType();
            this->index->fseek(0);
            let this->nodeLength = (this->elementCount + 1) * strlen(this->index->current());
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
        if node->getParentId() !== false {
            return this->locate(node->getParentId());
        }
        return false;
    }

    /**
    * Find the node containing the smallest/first key
    * @param int nodeNumber
    * @param array path
    */
    public function getFirstNode(var nodeNumber = false, array path = []) -> <\ZataBase\Storage\BTree\Node>|bool
    {
        var csv, node, element, byte;

        if nodeNumber === false {
            let nodeNumber = 0;
        }

        let byte = nodeNumber * this->nodeLength;

        let csv = this->index->getcsv(byte);

        if empty(csv) {
            return false;
        }

        this->index->fseek(byte);

        let node = Node::load(this->index);

        if node {
            let path[] = nodeNumber;
            for element in node->getElements() {
                if typeof element->getLess() == "int" {
                    return this->getFirstNode(element->getLess(), path);
                }
            }
            return node;
        }
        return false;
    }

    /**
    * Find the node containing the largest/last key
    * @param int nodeNumber
    * @param array path
    */
    public function getLastNode(var nodeNumber = false, array path = []) -> <\ZataBase\Storage\BTree\Node>|bool
    {
        var csv, node, element, byte;

        if nodeNumber === false {
            let nodeNumber = 0;
        }

        let byte = nodeNumber * this->nodeLength;

        let csv = this->index->getcsv(byte);

        if empty(csv) {
            return false;
        }

        this->index->fseek(byte);

        let node = Node::load(this->index);

        if node {
            let path[] = nodeNumber;
            for element in array_reverse(node->getElements()) {
                if typeof element->getMore() == "int" {
                    return this->getLastNode(element->getMore(), path);
                }
            }
            return node;
        }
        return false;
    }

    /**
    * Insert a row to the data file and add an index pointer
    * @param array index
    */
    public function insert(const array! row, var key = false)
    {
        var location, lastNode;

        let location = this->data->length();
        this->data->appendcsv(row);

        if key === false {
            if this->keyType != Element::KEY_INT {
                throw new Exception("A key must be provided if the key type is not an int.");
            }
            let lastNode = this->getLastNode();
            if lastNode {
                let key = lastNode->getLast()->getKey() + 1;
            }
            else {
                let key = 1;
            }
        }
        this->insertIndex([key, location]);
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
    public function splitNode(<\ZataBase\Storage\BTree\Node> node, <\ZataBase\Storage\BTree\Node\Element> element) -> array
    {
        var left, right, median, rightNode;

        node->addElement(element);

        let median = node->elements[ceil(count(node->elements) / 2) - 1];

        unset(node->elements[ceil(count(node->elements) / 2) - 1]);

        let right = array_slice(node->elements, ceil(count(node->elements) / 2));

        let left = array_slice(node->elements, 0, ceil(count(node->elements) / 2) - 1);

        node->setElements(left);

        median->setLess(node->getId());

        median->setMore(this->index->length() / this->nodeLength);

        median->setHasChildren(true);

        let rightNode = new Node(right, node->getParentId(), node->getPath());
        rightNode->setId(median->getMore());

        return [node, median, rightNode];
    }

    /**
    * Add an element to the root node which causes it to split
    * @param \ZataBase\Storage\BTree\Node\Element element
    */
    public function splitRoot(<\ZataBase\Storage\BTree\Node\Element> element)
    {
        var oldRoot, newRoot, nodeParts, left, right, median;

        this->index->callback(function (var line) {
            var csv, child, element;

            if substr(line, 0, 4) == "node" {
                let csv = str_getcsv(line);
                if !strlen(trim(csv[2])) {
                    let csv[2] = str_pad(0, 20);
                }
                else {
                    let child = trim(csv[2]);
                    let csv[2] = str_pad(child + 1, 20);
                }
                return str_pad(\ZataBase\Helper\Csv::arrayToCsv(csv), 85) . PHP_EOL;
            }

            let csv = str_getcsv(line);

            if !isset csv[4] {
                return line;
            }

            let element = new \ZataBase\Storage\BTree\Node\Element(csv[0], csv[1], csv[2], csv[3], csv[4]);

            element->incrementPointers(2);
            return element->toString() . PHP_EOL;
        });

        this->index->fseek(0);
        let oldRoot = Node::load(this->index);
        oldRoot->setPath([0, 1]);

        let nodeParts = this->splitNode(oldRoot, element);
        let left = nodeParts[0], median = nodeParts[1], right = nodeParts[2];

        this->index->fseek(0);
        this->index->fwrite(right->toString(this->elementCount), this->nodeLength - strlen(PHP_EOL));

        left->setParentId(0);
        this->index->prependRaw(left->toString(this->elementCount));

        median->setMore(2);
        let newRoot = new Node([median], false, [0]);
        this->index->prependRaw(newRoot->toString(this->elementCount));
    }

    /**
    * Add an element which splits a node and can recursively split up the tree
    * @param \ZataBase\Storage\BTree\Node node
    * @param \ZataBase\Storage\BTree\Node\Element element
    */
    public function split(<\ZataBase\Storage\BTree\Node> node, <\ZataBase\Storage\BTree\Node\Element> element, const bool doubleSplit = false)
    {
        var parent, nodeParts, left, right, median, rightElement, csv;

        let parent = this->getParent(node);

        if parent {
            let nodeParts = this->splitNode(node, element);
            let left = nodeParts[0], median = nodeParts[1], right = nodeParts[2];

            if parent->count() < this->elementCount {
                this->index->fseek(left->getId() * this->nodeLength);
                this->index->fwrite(left->toString(this->elementCount), this->nodeLength - strlen(PHP_EOL));

                right->setParentId(parent->getId());
                this->index->appendRaw(right->toString(this->elementCount));

                parent->addElement(median);
                this->index->fseek(parent->getId() * this->nodeLength);
                this->index->fwrite(parent->toString(this->elementCount), this->nodeLength - strlen(PHP_EOL));


                for rightElement in right->getElements() {
                    if rightElement->hasChildren {
                        if strlen(rightElement->less) {
                            let csv = this->index->getcsv(rightElement->less * this->nodeLength);
                            let csv[2] = str_pad(median->getMore(), 20);

                            this->index->fseek(rightElement->less * this->nodeLength);
                            this->index->fwrite(Csv::arrayToCsv([csv]), 85);
                        }

                        if strlen(rightElement->more) {
                            let csv = this->index->getcsv(rightElement->more * this->nodeLength);
                            let csv[2] = str_pad(median->getMore(), 20);

                            this->index->fseek(rightElement->more * this->nodeLength);
                            this->index->fwrite(Csv::arrayToCsv([csv]), 85);
                        }
                    }
                }
            }
            else {
                this->index->fseek(left->getId() * this->nodeLength);
                this->index->fwrite(left->toString(this->elementCount), this->nodeLength - strlen(PHP_EOL));

                right->setParentId(parent->getId());
                this->index->appendRaw(right->toString(this->elementCount));

                this->split(parent, median, true);
            }
        }
        else {
            this->splitRoot(element);
            if doubleSplit {
                this->index->fseek(2 * this->nodeLength);
                let node = Node::load(this->index);
                node->getLast()->incrementPointers(2);

                node->setPath([0, 2]);

                this->index->fseek(node->getId() * this->nodeLength);
                this->index->fwrite(node->toString(this->elementCount), this->nodeLength - strlen(PHP_EOL));

                for rightElement in node->getElements() {
                    if rightElement->hasChildren {
                        if strlen(rightElement->less) {
                            let csv = this->index->getcsv(rightElement->less * this->nodeLength);
                            let csv[2] = str_pad(2, 20);

                            this->index->fseek(rightElement->less * this->nodeLength);
                            this->index->fwrite(Csv::arrayToCsv([csv]), 85);
                        }

                        if strlen(rightElement->more) {
                            let csv = this->index->getcsv(rightElement->more * this->nodeLength);
                            let csv[2] = str_pad(2, 20);

                            this->index->fseek(rightElement->more * this->nodeLength);
                            this->index->fwrite(Csv::arrayToCsv([csv]), 85);
                        }
                    }
                }
            }
        }
    }

}