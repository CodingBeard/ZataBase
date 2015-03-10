/*
 * ArrayToObject
 * Borrowed in part from \Phalcon\Config
 * @category 
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Helper;

class ArrayToObject {

    public function __construct(const array! arrayConfig)
	{
		var key, value;

		for key, value in arrayConfig {
			let key = strval(key);
			if typeof value === "array" {
				let this->{key} = new self(value);
			} else {
				let this->{key} = value;
			}
		}
	}

}