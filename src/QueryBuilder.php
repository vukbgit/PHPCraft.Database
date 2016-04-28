<?php

namespace PHPCraft\Database;

use PHPCraft\Database\QueryBuilderInterface;

/**
 * Renders template using Twig (http://twig.sensiolabs.org/)
 *
 * @author vuk <info@vuk.bg.it>
 */
abstract class QueryBuilder implements QueryBuilderInterface
{
    /**
    * handles error exceptions, for a list of SQLSTATES see for example https://docs.oracle.com/cd/F49540_01/DOC/server.815/a58231/appd.htm
    *
    * @param mixed $exception thrown by adapter
    *
    * @throws DomainException if there is no logic defined to handle $sqlstate
    *
    * @return array|null first array element is the type of error, following elements are relevant informations according to error type
    */
    public function handleQueryException($exception)
    {
        $sqlstate = $exception->getCode();
        $message = $exception->getMessage();
        $error = null;
        switch($sqlstate) {
            //Integrity constraint violation
            case '23000':
                //foreign key?
                //extract table which holds the foreign key, it is always one even in the case of multiple table foreign key
                $pattern = '/a foreign key constraint fails \(`[a-zA-Z0-9_]+`.`([a-zA-Z0-9_]+)`/';
                preg_match($pattern,$message,$matches);
                if(!empty($matches)) {
                    //$matches[1] = referenced table
                    $error = array('integrity_constraint_violation_foreign_key',$matches[1]);
                    break;
                }
                //duplicate entry?
                $pattern = '/Duplicate entry \'[a-zA-Z0-9_-]+\' for key \'([a-zA-Z0-9_]+)\'/';
                preg_match($pattern,$message,$matches);
                if(!empty($matches)) {
                    //$matches[1] = key name
                    $error = array('integrity_constraint_violation_duplicate_entry',$matches[1]);
                    break;
                }
            break;
            //column not found
            case '42S22':
                $pattern = '/Unknown column \'([a-zA-Z0-9_]+)\'/';
                preg_match($pattern,$message,$matches);
                $error = array('column_not_found',$matches[1]);
            break;
            default:
                throw new DomainException(sprintf('SQL error code \'%s\' not handled with message\'%s\'',$sqlstate,$message));
            break;
        }
        return $error;
    }
}