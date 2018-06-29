<?php
/**
 * Created by PhpStorm.
 * User: Hovhannes Sinanyan
 * Date: 6/28/2018
 * Time: 3:52 AM
 */

namespace App\Controllers;


abstract class Controller
{
    protected $_model;

    public function __construct()
    {
        $this->_model = 'test';
    }
}