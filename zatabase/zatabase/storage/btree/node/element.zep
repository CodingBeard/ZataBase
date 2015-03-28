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
use ZataBase\Helper\Csv;

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
    * @var int|string
    */
    public less = "" {
        get, set
    };

    /**
    * Byte location of the node with greater keys than this element
    * @var int|string
    */
    public more = "" {
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
    const KEY_BLANK = 0;

    /**
    * @var int
    */
    const KEY_DETECT = 1;

    /**
    * @var int
    */
    const KEY_INT = 2;

    /**
    * @var int
    */
    const KEY_DATE = 3;

    /**
    * @var int
    */
    const KEY_DATETIME = 4;

    /**
    * @param array element
    */
    public function __construct(const int keyType, const var key, const var byte, const var less = "", const var more = "")
    {

        if keyType == self::KEY_DETECT {
            let this->key = key;
            let this->keyType = this->getType();
        }
        else {
            let this->keyType = keyType;
        }

        if this->keyType == self::KEY_INT {
            let this->key = (int) trim(key);
        }
        else {
            let this->key = trim(key);
        }

        let this->byte = (int) trim(byte);

        if strlen(trim(less)) {
            let this->less = (int) trim(less);
            let this->hasChildren = true;
        }

        if strlen(trim(more)) {
            let this->more = (int) trim(more);
            let this->hasChildren = true;
        }
    }

    /**
    * Work out the type of key we have
    */
    protected function getType() -> int
    {
        if typeof this->key == "string" {
            if \DateTime::createFromFormat("Y-m-d", this->key) !== false {
                return self::KEY_DATE;
            }
            elseif \DateTime::createFromFormat("Y-m-d H:i:s", this->key) !== false {
                return self::KEY_DATE;
            }
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
        else {

            return strtotime(key) - strtotime(this->key);
        }
    }

    /**
    * Return a string of the data stored in this object
    */
    public function toString() -> array
    {
        return Csv::arrayToCsv([this->keyType, str_pad(this->key, 20), str_pad(this->byte, 20), str_pad(this->less, 20), str_pad(this->more, 20)]);
    }

    /**
    * Return a blank string
    */
    public static function blankString() -> array
    {
        return str_pad("", 86);
    }
}