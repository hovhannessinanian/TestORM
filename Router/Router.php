<?php
/**
 * Created by PhpStorm.
 * User: Hovhannes Sinanyan
 * Date: 6/28/2018
 * Time: 3:03 AM
 */

namespace Router;



class Router
{
    private $_path;
    private $_routes;


    public function __construct($path)
    {
        $this->_path = (0 == strlen($path))? '/':$path;
        $this->_routes = require_once ('routes.php');
    }


    public static function loadPath($path)
    {
        $router = new self($path);

        $route = isset($router->_routes[$router->_path])?
            $router->_routes[$router->_path]:
            null;

        if($route){
            $route = explode('@', $route);
            $className = "App\Controllers\\". $route[0];
            $action = $route[1];
            $class = new $className();

            return call_user_func(array($class, $action));
        }
        return '404';
//        throw new \Exception("Unable to load $path.");
    }
}