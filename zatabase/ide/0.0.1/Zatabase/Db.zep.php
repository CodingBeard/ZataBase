<?php

namespace ZataBase;

class Db extends \ZataBase\Di\Injectable
{
    /**
     * Location of the auth file
     *
     * @var string
     */
    protected $authFile = ".auth";

    /**
     * Constructor
     *
     * @param array $parameters 
     */
	public function __construct($parameters) {}

}
