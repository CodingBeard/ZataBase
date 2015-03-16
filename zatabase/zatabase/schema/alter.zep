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
        if typeof after == "string" {
            let afterKey = this->table->columnKey(after) + 1;
            if afterKey === false {
                throw new Exception("You cannot add a column after a non-existent column.");
            }

            let columns = this->table->getColumns();
            let columns = array_merge(array_slice(columns, 0, afterKey, true), [column], array_slice(columns, afterKey, NULL, true));
            this->table->setColumns(columns);
        }
        else {
            let afterKey = count(this->table->getColumns());
            this->table->addColumn(column);
        }
        this->{"schema"}->saveTable(this->table);

        this->table->file->callback(
            function (var line, var offset) {

                let line = str_getcsv(line);

                array_splice(line, offset, 0, [null]);

                return \ZataBase\Helper\Csv::arrayToCsv(line) . PHP_EOL;
            },
            [afterKey]
        );

        return this;
    }

    /**
    * Delete a column from a table
    * @param string columnName
    */
    public function removeColumn(const string! columnName)
    {
        var column, columns, columnKey;

        let column = this->table->hasColumn(columnName);

        if !column {
            throw new Exception("You cannot remove a non-existent column from a table.");
        }

        let columns = this->table->getColumns();

        let columnKey = this->table->columnKey(columnName);

        unset(columns[columnName]);

        this->table->setColumns([]);

        for column in columns {
            this->table->addColumn(column);
        }
        this->{"schema"}->saveTable(this->table);

        this->table->file->callback(
            function (var line, var offset) {

                let line = str_getcsv(line);

                unset(line[offset]);

                let line = array_values(line);

                return \ZataBase\Helper\Csv::arrayToCsv(line) . PHP_EOL;
            },
            [columnKey]
        );
    }

    /**
    * Change a column in a table to the new supplied column
    * @param string columnName
    * @param \ZataBase\Table\Column column
    */
    public function changeColumn(const string! columnName, <\ZataBase\Table\Column> column)
    {
        var columns;

        let columns = this->table->getColumns();

        let columns[columnName] = column;

        this->table->setColumns([]);

        for column in columns {
            this->table->addColumn(column);
        }
        this->{"schema"}->saveTable(this->table);
    }

}