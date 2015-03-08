<?php

namespace ZataBase;

class Db
{
    /**
     * Storage adapter
     *
     * @var Storage\StorageInterface
     */
    protected $storage;
    /**
     * Traverser
     *
     * @var Traverser
     */
    protected $traverser;
    /**
     * Location of the auth file
     *
     * @var string
     */
    protected $authFile = ".auth";

    /**
     * Storage adapter
     *
     * @param Storage\StorageInterface $storage 
     */
	public function setStorage($storage) {}

    /**
     * Storage adapter
     *
     * @return Storage\StorageInterface 
     */
	public function getStorage() {}

    /**
     * Traverser
     *
     * @param Traverser $traverser 
     */
	public function setTraverser($traverser) {}

    /**
     * Traverser
     *
     * @return Traverser 
     */
	public function getTraverser() {}

    /**
     * Constructor
     *
     * @param Storage\File $adapter 
     * @param array $parameters 
     */
	public function __construct(Storage\Adapter\File $adapter, $parameters) {}

    /**
     * Create a table
     *
     * @param mixed $table 
     */
	public function createTable(Table $table) {}

    /**
     * Instance a table from the tables' file
     *
     * @param string $name 
     */
	public function getTable($name) {}

}
