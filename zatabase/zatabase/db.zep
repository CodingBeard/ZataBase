/*
 * Database
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase;

class db {

    public function __construct(const string! directory)
    {
        if !is_dir(directory) {
            mkdir(directory);
            chmod(directory, 770);
        }
    }

}