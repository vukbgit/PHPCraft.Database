<?php

namespace PHPCraft\Database;

use PHPCraft\Database\QueryBuilderInterface;

/**
 * Renders template using Twig (http://twig.sensiolabs.org/)
 *
 * @author vuk <info@vuk.bg.it>
 */
class PixieAdapter extends QueryBuilder
{

    private $queryBuilder;
    private $query;
    private $fetchMode;
    
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
    public function connect($driver, $host, $database, $username, $password, $charset = 'utf8', $collation = 'utf8_unicode_ci', $options = array())
    {
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
     * sets fetch method to be used by PDO
     *
     * @param string $mode, one of PDO predefined constants
     **/
    public function setFetchMode($mode)
    {
        $this->fetchMode = $mode;
    }
    
    /**
     * outputs query (for debugging purpose)
     **/
    public function outputQuery($type = 'select', $dataToBePassed = [])
    {
        r($this->query->getQuery($type, $dataToBePassed)->getRawSql());
    }
    
    /**
     * outputs query (for debugging purpose), shortcut to outputQuery()
     **/
    public function oq()
    {
        $this->outputQuery();
    }
    
    /**
     * sets table/view
     * @param string $table table or view name
     * @return Pixie\QueryBuilder\QueryBuilderHandler ($this->query)
     **/
    public function table($table)
    {
        $this->query = $this->queryBuilder->table($table);
        return $this->query;
    }
    
    /**
     * sets table/view fields to be extracted
     * @param array $fields
     * @return Pixie\QueryBuilder\QueryBuilderHandler ($this->query)
     **/
    public function fields($fields)
    {
        $this->query->select($fields);
        return $this->query;
    }
    
    /**
     * builds a raw parameter (not to be enclosed by quotes)
     * @param string $value
     * @return Pixie\QueryBuilder\Raw
     **/
    public function raw($value)
    {
        return $this->queryBuilder->raw($value);
    }    
    
    /**
     * sets a where condition
     * @param string $field
     * @param string $operator
     * @param mixed $value
     * @return Pixie\QueryBuilder\QueryBuilderHandler ($this->query)
     **/
    public function where($field, $operator = null, $value = null)
    {
        // If two params are given then assume operator is =
        if (func_num_args() == 2) {
            $value = $operator;
            $operator = '=';
        }
        $this->query->where($field, $operator, $value);
        return $this->query;
    }
    
    /**
     * sets a where IN condition 
     * @param string $field
     * @param array $values
     * @return Pixie\QueryBuilder\QueryBuilderHandler ($this->query)
     **/
    public function whereIn($field, $values)
    {
        $this->query->whereIn($field, $values);
        return $this->query;
    }
    
    /**
     * sets a limit condition 
     * @param int $limit
     * @return Pixie\QueryBuilder\QueryBuilderHandler ($this->query)
     **/
    public function limit($limit)
    {
        $this->query->limit($limit);
        return $this->query;
    }
    
    /**
     * orders query
     * @param string $field
     * @param string $direction
     * @return Pixie\QueryBuilder\QueryBuilderHandler
     **/
    public function orderBy($field, $direction)
    {
        $this->query->orderBy($field, $direction);
        return $this->query;
    }
    
    /**
     * execs a get statement
     * @return array of records
     **/
    public function get()
    {
        if($this->fetchMode) $this->query->setFetchMode($this->fetchMode);
        return $this->query->get();
    }
    
    /**
     * execs an insert statement
     * @param array $fields keys are fields names, values are fields values to be saved. In case array has two dimensions a batch insert is performed and an array of ids is returned
     * @return array|string $primary key value of inserted record(s)
     **/
    public function insert($fields)
    {
        return $this->query->insert($fields);
    }
    
    /**
     * execs an update statement
     * @param array $fields keys are fields names, values are fields values to be saved
     * @return unclear...
     **/
    public function update($fields)
    {
        return $this->query->update($fields);
    }
    
    /**
     * execs a delete statement
     * @param array $fields to be used for where condition keys are fields names, values are fields values
     * @throws Exception if no where conditions has been passed by means of $fields
     **/
    public function delete($fields)
    {
        if(empty($fields)) throw new \Exception('A where condition MUST be set for a delete statement');
        foreach($fields as $field => $value) {
            $this->where($field, $value);
        }
        $this->query->delete();
    }
}