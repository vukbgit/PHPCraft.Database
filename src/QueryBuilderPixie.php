<?php

namespace PHPCraft\Database;

use PHPCraft\Database\QueryBuilderInterface;

/**
 * Renders template using Twig (http://twig.sensiolabs.org/)
 *
 * @author vuk <info@vuk.bg.it>
 */
class QueryBuilderPixie implements QueryBuilderInterface
{

    private $queryBuilder;
    private $query;
    
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
    public function connect($driver, $host, $database, $username, $password, $charset = 'utf8', $collation = 'utf8_unicode_ci', $options = array()){
        $config = array(
                    'driver'    => $driver, // Db driver
                    'host'      => $host,
                    'database'  => $database,
                    'username'  => $username,
                    'password'  => $password,
                    'charset'   => $charset, // Optional
                    'collation' => $collation, // Optional
                    //'prefix'    => 'cb_', // Table prefix, optional
                    /*'options'   => array( // PDO constructor options, optional
                        PDO::ATTR_TIMEOUT => 5,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    )*/
                    'options'   => $options,
                );
        $connection = new \Pixie\Connection($driver, $config);
        $this->queryBuilder = new \Pixie\QueryBuilder\QueryBuilderHandler($connection);
    }
    
    /**
     * sets table/view
     *
     * @param string $table table or view name
     * @return Pixie\QueryBuilder\QueryBuilderHandler
     **/
    public function table($table){
        $this->query = $this->queryBuilder->table($table);
        return $this->query;
    }
    
    /**
     * outputs query (for debugging purpose)
     **/
    public function outputQuery(){
        r($this->query->getQuery()->getRawSql());
    }
    
    /**
     * outputs query (for debugging purpose), shortcut to outputQuery()
     **/
    public function oq(){
        $this->outputQuery();
    }

    /**
     * orders query
     * @param string $field
     * @param string $direction
     * @return Pixie\QueryBuilder\QueryBuilderHandler
     **/
    public function orderBy($field, $direction){
        return $this->query->orderBy($field, $direction);
    }
}