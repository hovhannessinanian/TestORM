<?php

namespace Builder;


class QueryBuilder
{
    private $_connection;
    protected $_result;
    protected $_fields;
    protected $_id;
    protected $_query = [];
    protected $_bindings = [];
    protected $_sql = '';
    protected $_table;
    protected $_className;
    protected $_add = '';
    protected $_limit;

    private $_preventWheres = false;


    public function __construct($className)
    {
        $this->_connection = PDOConnection::connect();
        $this->_className = $className;
    }


    public function table($tableName)
    {
        $this->_table = $tableName;
        return $this;
    }

    protected function save()
    {
        if(isset($this->_fields[$this->_id])){
            $this->update();
        } else {
            $this->create();
        }
        return $this->runQuery();
    }

    public function select($args = '*')
    {
        if (is_array($args)) {
            $args = "`". implode('` , `', $args) . "`";
        }
        $this->_query['action'] = 'SELECT';
        $this->_query['args'] = ' ' . trim($args) . ' ';
        return $this;
    }

    public function create()
    {
        $this->_query['action'] = 'INSERT';
        $this->_query['args'] = implode(', ', array_keys($this->_fields));
        $this->_bindings = array_values($this->_fields);
        return $this;
    }
    
    public function update()
    {
        $this->_query['action'] = 'UPDATE';
        $fields = $this->_fields;
        unset($fields[$this->_id]);
        $args = array_keys($fields);
        foreach ($args as $key => $value){
            $args[$key] = $value . '=?';
        }
        $this->_query['args'] = implode(',' , $args);
        $this->_bindings = array_values($fields);
        return $this;
    }

    
    public function delete()
    {
        $this->_query['action'] = 'DELETE';
        $this->_query['args'] = '';
        return $this->runQuery()->rowCount();
    }

    public function where($column, $operator, $value)
    {
        $this->_query['wheres'][] = '`'.$column.'`' . ' ' . $operator . ' ?';
        $this->_bindings[] = $value;
        return $this;
    }

    public function generateSql()
    {
        if (!isset($this->_query['action'])) {
            $this->select();
        }

        switch ($this->_query['action']) {
            case 'INSERT':
                $this->_sql = 'INSERT INTO ' . $this->_table .
                    ' ( '.$this->_query['args'].' ) VAlUES (' .
                    str_repeat('?,', count($this->_query['args'])). '?)';
                break;
            case 'UPDATE':
                $this->_sql = $this->_query['action'] . ' ' . $this->_table . ' SET ' .
                    $this->_query['args'];
                break;
            default:
                $this->_sql = $this->_query['action'] . ' ' . $this->_query['args'] . 'FROM ' . $this->_table . ' ';
                break;

        }
        $this->attachWheres();
        $this->addFilters();

        return $this;
    }


    public function get()
    {
        $query = $this->runQuery();
        while ($row = $query->fetchObject($this->_className)) {
            $this->_result[] = $row;
        }
        return $this->_result;
    }

    public function first()
    {
        $this->_limit = 1;
        $query = $this->runQuery();
        $this->_result = $query->fetchObject($this->_className);
        return $this->_result;
    }

    public function toSql()
    {
        $this->generateSql();
        return $this->_sql;
    }

    private function attachWheres()
    {
        if(isset($this->_fields[$this->_id])){
            $this->where($this->_id, ' = ', $this->_fields[$this->_id]);
        }

        if (isset($this->_query['wheres']) && !$this->_preventWheres) {
            $this->_sql .= 'WHERE ' . implode(' AND ', $this->_query['wheres']);
        }
        return $this;
    }

    private function addFilters()
    {
        if($this->_limit){
            $this->_sql .= ' LIMIT ' .$this->_limit;
        }
    }

    private function runQuery(){
        $this->generateSql();
        $query = $this->_connection->prepare($this->_sql);

        foreach ($this->_bindings as $key => $binding) {
            $key++;
            $query->bindParam($key, $binding);
        }

        $query->execute($this->_bindings);
        return $query;
    }

    protected function lastId(){
        return $this->_connection->lastInsertId();
    }
}