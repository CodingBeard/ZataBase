<?php

/*
 * Execute Test
 *
 * @category
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

use ZataBase\Db;
use ZataBase\Helper\ArrayToObject;
use ZataBase\Table;

class ExecuteTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \ZataBase\Db
     */
    protected $db;

    protected function setUp()
    {
        $this->db = new Db(new ArrayToObject([
            "databaseDir" => __DIR__ . "/database",
            "tablesDir" => "tables/"
        ]));
        $this->db->schema->deleteTable('Execute');
        $this->db->schema->createTable(new Table('Execute', [
             new Table\Column('one', Table\Column::INT_TYPE)
        ]));
    }

    /**
     * @covers            \ZataBase\Execute::__construct
     * @uses              \ZataBase\Execute
     */
    public function testConstruct()
    {
        $this->assertInstanceOf('\ZataBase\Execute', $this->db->execute);
    }

    /**
     * @covers            \ZataBase\Execute::insert
     * @uses              \ZataBase\Execute
     */
    public function testInsert()
    {
        $insert = $this->db->execute->insert('Execute');
        $this->assertInstanceOf('\ZataBase\Execute\Insert', $insert);
    }

    /**
     * @covers            \ZataBase\Execute::select
     * @uses              \ZataBase\Execute
     */
    public function testSelect()
    {
        $insert = $this->db->execute->select('Execute');
        $this->assertInstanceOf('\ZataBase\Execute\Select', $insert);
    }

    /**
     * @covers            \ZataBase\Execute::insert
     * @uses              \ZataBase\Execute
     */
    public function testDelete()
    {
        $insert = $this->db->execute->delete('Execute');
        $this->assertInstanceOf('\ZataBase\Execute\Delete', $insert);
    }

    /**
     * @covers            \ZataBase\Execute::insert
     * @uses              \ZataBase\Execute
     */
    public function testUpdate()
    {
        $insert = $this->db->execute->update('Execute');
        $this->assertInstanceOf('\ZataBase\Execute\Update', $insert);
    }

}
