<?php

namespace PHPCraft\Template;

/**
 * Renders template using Twig (http://twig.sensiolabs.org/)
 *
 * @author vuk <info@vuk.bg.it>
 */
class QueryBuilderPixie implements QueryBuilderInterface
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
        new \Pixie\Connection($driver, $config, 'QB');
    }
    
    /**
     * sets table/view
     *
     * @param string $table table or view name
     **/
    public function setTable($able){
        QB::table($able);
    }
}