/*
 * encoding benchmark
 *
 * @category 
 * @package 
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBaseBench;

class encoding
{
    public static function putgetcsv(const int iterations, const array values)
    {
        int i = 0;
        var file, line;
        let file = new \SplFileObject("../test1", "w+");
        while i < iterations {
            file->fputcsv(values);
            let i++;
        }
        file->rewind();

        while file->valid() {
            let line = file->fgetcsv();
        }
    }

    public static function json_endecode(const int iterations, const array values)
    {
        int i = 0;
        var file, line;
        let file = new \SplFileObject("../test2", "w+");
        while i < iterations {
            file->fwrite(json_encode(values) . PHP_EOL);
            let i++;
        }
        file->rewind();

        while file->valid() {
            let line = json_decode(file->current());
            file->next();
        }
    }

    public static function unserialize(const int iterations, const array values)
    {
        int i = 0;
        var file, line;
        let file = new \SplFileObject("../test3", "w+");
        while i < iterations {
            file->fwrite(serialize(values) . PHP_EOL);
            let i++;
        }
        file->rewind();

        while file->valid() {
            let line = unserialize(file->current());
            file->next();
        }
    }
}