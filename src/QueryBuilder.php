<?php

namespace PHPCraft\Database;

use PHPCraft\Database\QueryBuilderInterface;

/**
 * Query builder abstract class
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
    * @return array|null first array element is the type of error or false if error is not handled, following elements are relevant informations according to error type
    */
    public function handleQueryException($exception)
    {
        $sqlstate = $exception->getCode();
        $message = $exception->getMessage();
        $error = null;
        switch($sqlstate) {
            //Integrity constraint violation
            case '23000':
                //foreign key
                //extract table which holds the foreign key, it is always one even in the case of multiple table foreign key
                $pattern = '/a foreign key constraint fails \(`[a-zA-Z0-9_]+`.`([a-zA-Z0-9_]+)`/';
                preg_match($pattern,$message,$matches);
                if(!empty($matches)) {
                    //$matches[1] = referenced table
                    $error = array('integrity_constraint_violation_foreign_key',$matches[1]);
                    break;
                }
                //duplicate entry
                $pattern = '/Duplicate entry \'[^\']+\' for key \'([a-zA-Z0-9_]+)\'/';
                preg_match($pattern,$message,$matches);
                if(!empty($matches)) {
                    //$matches[1] = key name
                    $error = array('integrity_constraint_violation_duplicate_entry',$matches[1]);
                    break;
                }
                //non null column
                $pattern = '/Column \'([ a-zA-Z0-9_-]+)\' cannot be null/';
                preg_match($pattern,$message,$matches);
                if(!empty($matches)) {
                    //$matches[1] = key name
                    $error = array('integrity_constraint_violation_non_null_column',$matches[1]);
                    break;
                }
            break;
            //column not found
            case '42S22':
                $pattern = '/Unknown column \'([a-zA-Z0-9_]+)\'/';
                preg_match($pattern,$message,$matches);
                $error = array('column_not_found',$matches[1]);
            break;
            //unique violation
            case '23505':
                $pattern = '/Key \(([a-zA-Z0-9_]+)\)/';
                preg_match($pattern,$message,$matches);
                $error = array('integrity_constraint_violation_duplicate_entry',$matches[1]);
            break;
            //foreign key violation
            case '23503':
                $pattern = '/foreign key constraint "([a-zA-Z0-9_]+)"/';
                preg_match($pattern,$message,$matches);
                $error = array('foreign_key_violation',$matches[1]);
            break;
            default:
                //throw new \DomainException(sprintf('SQL error code \'%s\' not handled with message\'%s\'',$sqlstate,$message));
                $error = array(false,$message);
            break;
        }
        return $error;
    }
}