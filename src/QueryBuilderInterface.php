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
    
    /**
     * execs an insert statement
     * @param array $fields keys are fields names, values are fields values to be saved
     **/
    public function insert($fields);
    
    /**
     * execs an update statement
     * @param array $fields keys are fields names, values are fields values to be saved
     **/
    public function update($fields);
    
    /**
     * execs a delete statement
     * @param array $fields to be used for where condition keys are fields names, values are fields values
     **/
    public function delete($fields);
    
    /**
    * handles error messages, for a list of SQLSTATES see for example https://docs.oracle.com/cd/F49540_01/DOC/server.815/a58231/appd.htm
    *
    * @param mixed $exception thrown by adapter
    *
    * @throws DomainException if there is no logic defined to handle $sqlstate
    *
    * @return array|null first array element is the type of error, following elements are relevant informations according to error type
    */
    public function handleQueryException($exception);
}