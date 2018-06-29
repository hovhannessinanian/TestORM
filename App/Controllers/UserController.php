<?php

namespace App\Controllers;


use App\Models\User;

class UserController extends Controller
{

    public function getUsers()
    {
        return "BRAVO HOVOOOOOO";
    }

    public function testUserQuery()
    {
//        $user = new User();
//        $user->name = 'Banana';
//        echo "<pre>";
//        print_r($user->name);
//        echo "</pre>";

//        $users = new User();
//        $users->get();
//        echo "<pre>";
//        print_r($users);
//        echo "</pre>";

//        $user = new User();
//        $user->name = "Vazgen is here";
//        $user->email = "vazgen@gmail.com";
//        $user->save();
////        if ($user->save()) {
//            echo "<pre>";
//            print_r($user);
//            echo "</pre>";
////        }

//        $user = new User();
//        $user = $user->first();
//
//        $user->email = 'banan@gmail.com';
//        $user->save();
//
//        echo "<pre>";
//            print_r($user);
//            echo "</pre>";

        $user = new User();

        $user->where('name', 'like', '%Vazgen%')->delete();

    }
}