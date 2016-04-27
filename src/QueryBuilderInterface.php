<?php

namespace PHPCraft\Database;

/**
 * Interface to query builder
 *
 * @author vuk <info@vuk.bg.it>
 */
interface QueryBuilderInterface
{
    
    /**
     * connects to database
     *
     * @param string $driver database type
     * @param string $host
     * @param string $database
     * @param string $username
     * @param string $password
     * @param string $charset
     * @param string $collation
     * @param array $options
     **/
    public function connect($driver, $host, $database, $username, $password, $charset = false, $collation = false, $options = array());
    
    /**
     * sets table/view
     *
     * @param string $table table or view name
     **/
    public function table($table);
}