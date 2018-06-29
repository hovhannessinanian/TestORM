<?php

namespace Builder;

use PDO;
use PDOException;

class PDOConnection
{

    private $_connection = null;

    private function __construct()
    {
        if (null == $this->_connection) {
            $config = include ('config.php');
            $dbo = $config['DB_DRIVER'] .
                ':host=' . $config['DB_HOST'] .
                ';port=' . $config['DB_PORT'] .
                ';dbname=' . $config['DB_NAME'];

            try{
                $this->_connection = new PDO($dbo, $config['DB_USERNAME'], $config['DB_PASSWORD'],
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            } catch (PDOException $error){
                die('PDO Exception : ' . $error->getMessage());
            }
        }
        return $this->_connection;
    }

    public static function connect()
    {
        $connection = new self();
        return $connection->_connection;
    }
}
