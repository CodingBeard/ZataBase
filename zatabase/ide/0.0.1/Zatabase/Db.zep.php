<?php

namespace ZataBase;

class Db
{
    /**
     * Storage adapter
     *
     * @var storage Storage\StorageInterface
     */
    protected $storage;
    /**
     * Location of the auth file
     *
     * @var authFile string
     */
    protected $authFile = ".auth";

    /**
     * @param mixed $adapter 
     * @param array $parameters 
     */
	public function __construct(Storage\StorageInterface $adapter, $parameters) {}

    /**
     * @return Storage\StorageInterface 
     */
	public function getStorage() {}

}
