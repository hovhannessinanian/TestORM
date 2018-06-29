<?php

namespace App;
use Builder\PDOConnection;
use Builder\QueryBuilder;


class Test
{
    public function __construct()
    {
        die('I am Test');
    }

    public function run()
    {
        $test = new QueryBuilder();
        $sql = $test->table('users')
//            ->where('id', '>', 0)
//            ->where('name', 'LIKE', '%nes%')
            ->get();
        echo "<pre>";
        print_r($sql);
        echo "</pre>";
        return false;
    }
}