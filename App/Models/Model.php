<?php
/**
 * Created by PhpStorm.
 * User: Hovhannes Sinanyan
 * Date: 6/28/2018
 * Time: 4:21 AM
 */

namespace App\Models;

use Builder\QueryBuilder;

abstract class Model extends QueryBuilder
{
//    protected $_className;
    protected $_id = 'id';
    
    public function __construct()
    {
        $childClassName = get_called_class();
        parent::__construct($childClassName);
        $tableName = basename(str_replace('\\', '/', get_called_class()));
        if(!$this->_table)
        $this->table(str_replace('\\','', strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $tableName))));
    }

    public function __get($param)
    {
        return $this->_fields[$param];
    }

    public function __set($param, $value)
    {
        $this->_fields[$param] = $value;
    }

    public function save()
    {
        parent::save();
        $this->_fields[$this->_id] = parent::lastId();
    }


}