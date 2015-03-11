/*
 * ZataBase\Helper\FileHandler
 *
 * @category 
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Helper;

class FileHandler extends \SplFileObject {

    /**
    * Add a line to the end of the file
    * @param string content
    */
    public function append(const var content)
    {
        this->fseek(0, SEEK_END);
        this->fwrite(content . PHP_EOL, strlen(content . PHP_EOL));
    }

    /**
    * Return the number of lines in the file
    */
    public function count()
    {
        var count;
        this->seek(PHP_INT_MAX);
        let count = this->key() - 1;
        this->rewind();
        return count;
    }

    /**
    * Delete a line from the file
    * @param int offset line number
    */
    public function delete(var offset)
    {
        var rewrite;
        array offsets;

        if typeof offset != "array" {
            let offsets = [offset];
        }
        else {
            let offsets = offset;
        }

        let rewrite = new self(this->getRealPath() . ".write", "w");
        this->rewind();
        while this->valid() {
            if !in_array(this->ftell(), offsets) {
                rewrite->fwrite(this->current());
            }
            this->current();
            this->next();
        }
        rename(this->getRealPath() . ".write", this->getRealPath());
    }

}