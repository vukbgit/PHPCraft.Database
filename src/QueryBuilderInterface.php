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
     * sets fetch method to be used by PDO
     *
     * @param string $mode, one of PDO predefined constants
     **/
    public function setFetchMode($mode);
    
    /**
     * sets table/view. It should create the internal 'query' object and store it for following operations
     *
     * @param string $table table or view name
     **/
    public function table($table);
    
    /**
     * sets table/view fields to be extracted
     *
     * @param array $fields
     **/
    public function fields($fields);
    
    /**
     * outputs query (for debugging purpose)
     **/
    public function outputQuery();
    
    /**
     * outputs query (for debugging purpose), shortcut to outputQuery()
     **/
    public function oq();
    
    /**
     * sets a where condition
     * @param string $field
     * @param string $operator
     * @param mixed $value
     **/
    public function where($field, $operator = null, $value = null);
    
    /**
     * orders query
     * @param string $field
     * @param string $direction
     **/
    public function orderBy($field, $direction);
    
    /**
     * execs a get statement
     **/
    public function get();
}