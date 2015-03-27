/*
 * Node
 *
 * @category 
 * @package 
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Storage\BTree;

use ZataBase\Storage\BTree\Node\Element;
use ZataBase\Storage\Exception;

class Node
{
    /**
    * array of Element objects
    * @var int
    */
    public elements {
        set, get
    };

    /**
    * @param array elements
    */
    public function __construct(const array! elements)
    {
        let this->elements = elements;
    }

    /**
    * Load a node from a file handler
    * @param \ZataBase\Helper\FileHandler file
    */
    public static function load(<\ZataBase\Helper\FileHandler> file) -> <\ZataBase\Storage\BTree\Node>|bool
    {
        var nodeInfo, csv, elements;
        int count = 0;

        let nodeInfo = file->fgetcsv();

        if nodeInfo[0] != "node" {
            return false;
        }

        let elements = [];

        while count < trim(nodeInfo[1]) {
            file->next();
            let csv = file->fgetcsv();
            let elements[] = new Element(csv[0], csv[1], csv[2], csv[3], csv[4]);
            let count++;
        }

        return new self(elements);
    }

    /**
    * The number of elements in this node
    */
    public function count() -> int
    {
        return count(this->elements);
    }

    /**
    * Sort the elements in the node
    */
    public function sort()
    {
        if this->elements[0]->getKeyType() == Element::KEY_INT {

            usort(this->elements, function (var a, var b) {
                if a->getKey() == b->getKey() {
                    return 0;
                }
                elseif a->getKey() > b->getKey() {
                    return 1;
                }
                else {
                    return -1;
                }
            });
        }
        elseif this->elements[0]->getKeyType() == Element::KEY_DATE {

            usort(this->elements, function (var a, var b) {
                if strtotime(a->getKey()) == strtotime(b->getKey()) {
                    return 0;
                }
                elseif strtotime(a->getKey()) > strtotime(b->getKey()) {
                    return 1;
                }
                else {
                    return -1;
                }
            });
        }
    }

    /**
    * @param \ZataBase\Storage\BTree\Node\Element element
    */
    public function addElement(const <\ZataBase\Storage\BTree\Node\Element> element)
    {
        let this->elements[] = element;
        this->sort();
    }

    /**
    * @param \ZataBase\Storage\BTree\Node\Element element
    */
    public function removeElement(const string elementKey)
    {
        var key, element;

        for key, element in this->elements {
            if element->getKey() == elementKey {
                unset(this->elements[key]);
                return;
            }
        }
    }

    /**
    * Check if this node contains an element with key
    * if not, but we know where the node containing said element is, return that
    * else return false
    */
    public function hasKey(const var key) -> <\ZataBase\Storage\BTree\Node\Element>|int|bool
    {
        var element, distance, closestDistance = -1, closestElement = 0;

        for element in this->elements {
            if element->getKey() == key {
                return element;
            }
        }

        for element in this->elements {

            if element->hasChildren {

                if closestDistance == -1 {

                    let closestDistance = abs(element->distance(key));
                    let closestElement = element;
                }
                else {

                    let distance = abs(element->distance(key));

                    if distance < closestDistance {
                        let closestDistance = distance;
                        let closestElement = element;
                    }
                }
            }
        }

        if typeof closestElement == "object" {
            if closestElement->distance(key) < 0 {
                return closestElement->getLess();
            }
            else {
                return closestElement->getMore();
            }
        }

        return false;
    }

    /**
    * Get the first element
    */
    public function getFirst() -> <\ZataBase\Storage\BTree\Node\Element>|bool
    {
        return reset(this->elements);
    }

    /**
    * Get an Element
    */
    public function getElement(const int key) -> <\ZataBase\Storage\BTree\Node\Element>|bool
    {
        if isset this->elements[key] {
            return this->elements[key];
        }
        return false;
    }

    /**
    * Get the last element
    */
    public function getLast() -> <\ZataBase\Storage\BTree\Node\Element>|bool
    {
        return end(this->elements);
    }

    /**
    * Convert self to a string for storage
    */
    public function toString() -> string
    {
        var toString, element;

        let toString = "node," . str_pad(this->count(), 20) . PHP_EOL;

        for element in this->elements {
            let toString .= element->toString() . PHP_EOL;
        }

        return substr(toString, 0, -1);
    }

}