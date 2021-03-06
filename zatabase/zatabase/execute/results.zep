/*
 * ZataBase\Execute\Results
 *
 * @category 
 * @package 
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Execute;

class Results implements \SeekableIterator, \ArrayAccess {

    /**
    * Table's file path
    * @var string
    */
    protected table;

    /**
    * Internal row position
    * @var array
    */
    protected position;

    /**
    * Byte seek locations of each row
    * @var array
    */
    protected rows {
        get
    };

    /**
    * Constructor
    * @param string table
    */
    public function __construct(const <\ZataBase\Table> table)
    {
        let this->table = table;
        let this->position = 0;
    }

    /**
    * Add a row
    * @var int offset
    */
    public function addRowOffset(offset)
    {
        let this->rows[] = offset;
    }

    /**
    * Count the rows
    */
    public function count()
    {
        return count(this->rows);
    }

    /**
    * Get a row from its offset
    * @var int offset
    */
    public function getRow(var offset = false) -> bool|array
    {
        return this->table->file->getcsv(this->rows[offset]);
    }

    /**
    * Get an offset from its row key
    * @var int row
    */
    public function getOffset(const int! rowKey)
    {
        var offset;
        if fetch offset, this->rows[rowKey] {
            return offset;
        }
        return false;
    }

    /**
    * Convert result set to an array with all values loaded, don't use for heavy memory selects
    */
    public function toArray() -> array
    {
        var offset;
        array arrayResults = [];

        if typeof this->rows == "array" {
            for offset in this->rows {
                let arrayResults[] = this->table->file->getcsv(offset);
            }
            return arrayResults;
        }
        else {
            return [];
        }
    }


    /* Methods required for SeekableIterator and arrayAccess */

    public function seek(const int position) {
      if !isset(this->rows[position]) {
          throw new \OutOfBoundsException("invalid seek position ($position)");
      }

      let this->position = position;
    }

    public function rewind() {
        let this->position = 0;
    }

    public function current() {
        return this->getRow(this->position);
    }

    public function key() {
        return this->position;
    }

    public function next() {
        let this->position++;
    }

    public function valid() {
        return isset(this->rows[this->position]);
    }

    public function offsetSet(const var offset = null, const var value) {
        if is_null(offset) {
            let this->rows[] = value;
        } else {
            let this->rows[offset] = value;
        }
    }

    public function offsetExists(const var offset) {
        return isset(this->rows[offset]);
    }

    public function offsetUnset(const var offset) {
        unset(this->rows[offset]);
    }

    public function offsetGet(const var offset) {
        return isset(this->rows[offset]) ? this->getRow(offset) : null;
    }
}