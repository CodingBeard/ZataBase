<?php

namespace ZataBase;

class Db extends \ZataBase\Di\Injectable
{

    /**
     * Constructor
     *
     * @param mixed $config 
     */
	public function __construct(\ZataBase\Helper\ArrayToObject $config) {}

    /**
     * Alias of Schema::createTable
     *
     * @param Table $table 
     */
	public function createTable(\ZataBase\Table $table) {}

    /**
     * Alias of Schema::deleteTable
     *
     * @param string $tableName 
     */
	public function deleteTable($tableName) {}

    /**
     * Alias of Schema::alterTable
     *
     * @param string $tableName 
     * @return \ZataBase\Schema\Alter 
     */
	public function alterTable($tableName) {}

    /**
     * Alias of Execute::insert
     *
     * @param string $tableName 
     * @return \ZataBase\Execute\Insert 
     */
	public function insert($tableName) {}

    /**
     * Alias of Execute::select
     *
     * @param string $tableName 
     * @param array $parameters 
     * @return \ZataBase\Execute\Select 
     */
	public function select($tableName) {}

    /**
     * Alias of Execute::delete
     *
     * @param string $tableName 
     * @param array $parameters 
     * @return \ZataBase\Execute\Select 
     */
	public function delete($tableName) {}

    /**
     * Alias of Execute::update
     *
     * @param string $tableName 
     * @param array $parameters 
     * @return \ZataBase\Execute\Update 
     */
	public function update($tableName) {}

}
