/*
 * ZataBase\Execute\Condition\Like
 *
 * @category 
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Execute\Condition;

class Like {

    /**
    * True by default, false if this condition is notted
    * @var bool
    */
    protected matches = true {
        set, get
    };

    /**
    * Column ID
    * @var int
    */
    protected column {
        set, get
    };

    /**
    * Pattern to check against
    * @var mixed
    */
    protected pattern {
        set, get
    };

    /**
    * @param string tableName
    */
    public function __construct(const bool! isNot, const <\Zatabase\Table\Column> column, const var value)
    {
        let this->matches = isNot ? false : true;
        let this->column = column;

        if stripos(value, "\\%") !== false {
            let this->pattern = "#^" . str_replace("\\%", "%", value) . "$#is";
        }
        elseif stripos(value, "\\_") !== false {
            let this->pattern = "#^" . str_replace("\\_", "_", value) . "$#is";
        }
        elseif stripos(value, "%") !== false {
            let this->pattern = "#^" . preg_replace("#(?<!\\\\)%#is", "(.*)", value) . "$#is";
        }
        elseif stripos(value, "_") !== false {
            let this->pattern = "#^" . preg_replace("#(?<!\\\\)_#is", "(.)", value) . "$#is";
        }
        else {
            let this->pattern = "#^" . value . "$#is";
        }

    }

    public function matches(const array! row) -> bool
    {
        if preg_match(this->pattern, row[this->column->getKey()]) {
            return this->matches;
        }
        return !this->matches;
    }
}