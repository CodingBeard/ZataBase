/*
 * ZataBase\Schema\Alter
 *
 * @category 
 * @package 
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Schema;

use ZataBase\Di\Injectable;
use ZataBase\Table;

class Alter extends Injectable {

    /**
    * Table
    * @var \Zatabase\Table
    */
    protected table;

    /**
    * @param \Zatabase\Table table
    */
    public function __construct(<\Zatabase\Table> table)
    {
        let this->table = table;
    }

    /**
    * Add a column to a table
    * @param \ZataBase\Table\Column column
    * @param string after
    */
    public function addColumn(<\ZataBase\Table\Column> column, const var after = false) -> <\ZataBase\Schema\Alter>
    {
        var columns, afterKey;
        if after {
            let afterKey = this->table->columnKey(after);
            if afterKey === false {
                throw new Exception("You cannot add a column after a non-existent column.");
            }

            let columns = this->table->getColumns();
            let columns = this->spliceColumns(columns, column, afterKey);
            this->table->setColumns(columns);
        }
        else {
            let afterKey = count(this->table->getColumns());
            this->table->addColumn(column);
        }
        this->{"schema"}->saveTable(this->table);

        this->table->file->callback(
            function (var line, var offset) {

                let line = json_decode(line);

                array_splice(line, offset, 0, [null]);

                return json_encode(line) . PHP_EOL;
            },
            [afterKey]
        );

        return this;
    }

    /**
    * Add a column at a specific offset
    * @param \ZataBase\Table\Column column
    * @param string after
    */
    protected function spliceColumns(array! columns, <\ZataBase\Table\Column> column, const int! offset) -> array
    {
        return array_slice(columns, 0, offset, true) + column + array_slice(columns, offset, NULL, true);
    }

}