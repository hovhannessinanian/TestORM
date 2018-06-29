<?php

//require_once ('../App/Test.php');
//require_once ('../Builder/PDOConnection.php');
//require_once ('../Builder/QueryBuilder.php');


function __autoload($class_name) {
    if(file_exists($class_name . '.php')) {
        require_once($class_name . '.php');
    } else {
        throw new Exception("Unable to load $class_name.");
    }
}

try {
//    $a = new Router\Router($_REQUEST['path']);
//    var_dump($a);
    $view = \Router\Router::loadPath($_REQUEST['path']);
    echo $view;
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}


//require_once ('../Router/Router.php');

//function start()
//{
////    echo "<pre>";
////    print_r($_REQUEST['path']);
////    echo "</pre>";
////    die;
////    $test = new \App\Test();
////    return $test->run();
//    return new \Router\Router($_REQUEST['path']);
//}

//start();

