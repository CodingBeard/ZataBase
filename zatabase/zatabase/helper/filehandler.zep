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
    public function appendRaw(const var content)
    {
        this->fseek(0, SEEK_END);
        this->fwrite(content . PHP_EOL, strlen(content . PHP_EOL));
    }

    /**
    * Return the number of lines in the file
    */
    public function count() -> int
    {
        var count;
        this->seek(PHP_INT_MAX);
        let count = this->key();
        this->rewind();
        return count;
    }

    /**
    * Return the positions of the start of each line of the file
    */
    public function getLinePositions() -> array
    {
        array positions;

        this->fseek(0, SEEK_SET);

        let positions = [];

        while this->valid() {
            let positions[] = this->ftell();
            this->current();
            this->next();
        }
        array_pop(positions);
        return positions;
    }

    /**
    * Delete line(s) from the file
    * @param int offset line number
    */
    public function delete(const var offset)
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
        parent::__construct(this->getRealPath(), "c+");
    }

    /**
    * Replace line(s) in the file
    * @param int offset line number
    * @param string content
    */
    public function replace(const var offset, const var content)
    {
        var rewrite, offsetKey;
        array offsets, contents;

        if typeof offset != "array" {
            let offsets = [offset];
        }
        else {
            let offsets = offset;
        }

        if typeof content != "array" {
            let contents = [content];
        }
        else {
            let contents = content;
        }

        let rewrite = new self(this->getRealPath() . ".write", "w");
        this->rewind();
        while this->valid() {
            let offsetKey = array_search(this->ftell(), offsets);
            if offsetKey === false {
                rewrite->fwrite(this->current());
            }
            else {
                rewrite->fwrite(contents[offsetKey] . PHP_EOL);
            }
            this->current();
            this->next();
        }
        rename(this->getRealPath() . ".write", this->getRealPath());
        parent::__construct(this->getRealPath(), "c+");
    }

    /**
    * Perform a callback on line(s) of a file
    * @param int offset line number
    */
    public function callback(const <\Closure> callback, const array arguments = [], const var offsets = false)
    {
        var rewrite, line;

        let rewrite = new self(this->getRealPath() . ".write", "w");
        this->rewind();
        if typeof offsets == "array" {
            while this->valid() {
                if in_array(this->ftell(), offsets) {
                    rewrite->fwrite(call_user_func_array(callback, array_merge([this->current()], arguments)));
                }
                else {
                    rewrite->fwrite(this->current());
                }
                this->next();
            }
        }
        else {
            while this->valid() {
                let line = this->current();
                if strlen(line) {
                    rewrite->fwrite(call_user_func_array(callback, array_merge([this->current()], arguments)));
                }
                this->next();
            }
        }
        rename(this->getRealPath() . ".write", this->getRealPath());
        parent::__construct(this->getRealPath(), "c+");
    }

    /**
    * Add a csv line to the file
    * @param array values
    */
    public function putcsv(const int! offset, const array! values) -> int
    {
        this->fseek(offset, SEEK_SET);
        return this->fputcsv(values, ",", "\"");
    }

    /**
    * Add a csv line to the file
    * @param array values
    */
    public function appendcsv(const array! values) -> int
    {
        this->fseek(0, SEEK_END);
        return this->fputcsv(values, ",", "\"");
    }

    /**
    * Add a csv line to the file
    * @param array values
    */
    public function appendcsvs(const array! values) -> int
    {
        var row, length = 0;
        this->fseek(0, SEEK_END);

        for row in values {
            let length = length + this->fputcsv(row, ",", "\"");
        }
        return length;
    }

    /**
    * Replace line(s) in the file
    * @param int offset line number
    * @param string content
    */
    public function replacecsv(const var offset, const var content)
    {
        var rewrite, offsetKey;
        array offsets, contents;

        if typeof offset != "array" {
            let offsets = [offset];
        }
        else {
            let offsets = offset;
        }

        if typeof content != "array" {
            let contents = [content];
        }
        else {
            let contents = content;
        }

        let rewrite = new self(this->getRealPath() . ".write", "w");
        this->rewind();
        while this->valid() {
            let offsetKey = array_search(this->ftell(), offsets);
            if offsetKey === false {
                rewrite->fwrite(this->current());
            }
            else {
                rewrite->fputcsv(contents[offsetKey]);
            }
            this->current();
            this->next();
        }
        rename(this->getRealPath() . ".write", this->getRealPath());
        parent::__construct(this->getRealPath(), "c+");
    }

    /**
    * Get a csv line from the file
    * @param int offset
    */
    public function getcsv(const var offset = false) -> array|bool
    {
        var line;

        if offset !== false {
            this->fseek(offset, SEEK_SET);
        }

        let line = this->current();

        if strlen(line) {
            return str_getcsv(line);
        }
        return false;
    }

}