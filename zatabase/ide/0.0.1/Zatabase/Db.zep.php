<?php

namespace ZataBase;

class Db extends \ZataBase\Di\Injectable
{

    /**
     * Constructor
     *
     * @param mixed $config 
     */
	public function __construct($config) {}

    /**
     * Alias of Execute\Insert
     *
     * @param string $tableName 
     * @return Execute\Insert 
     */
	public function insert($tableName) {}

    /**
     * Alias of Execute\Select
     *
     * @param string $tableName 
     * @param array $parameters 
     * @return Execute\Select 
     */
	public function select($tableName) {}

    /**
     * Alias of Execute\Delete
     *
     * @param string $tableName 
     * @param array $parameters 
     * @return Execute\Select 
     */
	public function delete($tableName) {}

    /**
     * Alias of Execute\Update
     *
     * @param string $tableName 
     * @param array $parameters 
     * @return Execute\Update 
     */
	public function update($tableName) {}

}
