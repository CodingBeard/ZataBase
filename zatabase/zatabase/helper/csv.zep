/*
 * csv
 *
 * @category 
 * @package 
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Helper;

class Csv
{
    public static function arrayToCsv(const array! toConvert) -> string
    {
        var value, csv = "";

        for value in toConvert {
            if typeof value == "array" {
                let csv .= Csv::arrayToCsv(value);
            }
            else {
                let csv .= addslashes(value) . ",";
            }
        }

        return substr(csv, 0, -1);
    }
}