/*
 * Element
 *
 * @category 
 * @package 
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Storage\BTree\Node;

use ZataBase\Storage\Exception;

class Element
{
    /**
    * Element's key type
    * @var int
    */
    public keyType {
        get, set
    };

    /**
    * Element's key
    * @var mixed
    */
    public key {
        get, set
    };

    /**
    * Byte location of this element's key's data
    * @var int
    */
    public byte {
        get, set
    };

    /**
    * Byte location of the node with smaller keys than this element
    * @var int
    */
    public less {
        get, set
    };

    /**
    * Byte location of the node with greater keys than this element
    * @var int
    */
    public more {
        get, set
    };

    /**
    * @var bool
    */
    public hasChildren = false {
        get, set
    };

    /**
    * @var int
    */
    const KEY_INT = 1;

    /**
    * @var int
    */
    const KEY_STRING = 2;

    /**
    * @var int
    */
    const KEY_DATE = 3;

    /**
    * Pass in an array with 4/6 elements in it [key, byteLocation, lessNodeByte?, moreNodeByte?]
    * @param array element
    */
    public function __construct(array element)
    {
        if element->count() !== 3 && element->count() !== 4 && element->count() !== 5 {
            throw new Exception("Element should be an array with 3-5 elements in it [keyType, key, dataByte, lessNodeByte?, moreNodeByte?]");
        }
        if element->count() == 3 {
            let this->key = element[1];
            let this->byte = (int) element[2];
        }
        elseif element->count() == 4 {
            let this->hasChildren = true;
            let this->key = element[1];
            let this->byte = (int) element[2];
            let this->less = (int) element[3];
        }
        elseif element->count() == 5 {
            let this->hasChildren = true;
            let this->key = element[1];
            let this->byte = (int) element[2];
            let this->less = (int) element[3];
            let this->more = (int) element[4];
        }

        if abs(element[0]) {
            let this->keyType = (int) element[0];
        }
        else {
            let this->keyType = this->getType();
        }

        if this->keyType == self::KEY_INT {
            let this->key = (int) this->key;
        }
    }

    /**
    * Work out the type of key we have
    */
    protected function getType() -> int
    {
        if typeof this->key == "string" {

            if \DateTime::createFromFormat("Y-m-d", this->key) !== false
            || \DateTime::createFromFormat("Y-m-d H:i:s", this->key) !== false {

                return self::KEY_DATE;
            }

            return self::KEY_STRING;
        }

        return self::KEY_INT;
    }

    /**
    * Check how far the supplied key is from our key
    * @param int|string key
    */
    public function distance(const var key) -> int
    {
        if this->keyType == self::KEY_INT {

            return key - this->key;
        }
        elseif this->keyType == self::KEY_STRING {

            return strcmp(key, this->key);
        }
        else {

            return strtotime(key) - strtotime(this->key);
        }
    }

    /**
    * Return an array of the data stored in this object
    */
    public function toArray() -> array
    {
        if typeof this->less == "int" {

            if typeof this->more == "int" {

                return [this->keyType, this->key, this->byte, this->less, this->more];
            }
            return [this->keyType, this->key, this->byte, this->less];
        }
        return [this->keyType, this->key, this->byte];
    }
}