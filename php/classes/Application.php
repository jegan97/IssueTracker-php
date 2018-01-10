<?php

session_start();

/**
 * Created by PhpStorm.
 * User: JEGAN'S BEAST
 * Date: 12/15/2016
 * Time: 2:27 PM
 */
include_once("User.php");
include_once("DB.php");

class Application
{

    /** @var User $user**/
    private static $user;


    public static function isLoggedIn(){

        if(isset($_SESSION["user"])){
            Application::$user = unserialize($_SESSION["user"]);
            return true;
        }
        return false;
    }

    public static function getLoggedInUserID(){
        return unserialize($_SESSION["user"])->getUserid();
    }


    /**
     * @return User
     */
    public static function getLoggedInUser(){
        return unserialize($_SESSION["user"]);
    }

    public static function logout(){
       if(isset($_SESSION["user"])) {
           unset($_SESSION["user"]);
       }
    }

    public static function login($email,$password){
        $u = DB::getDB()->isUserValid($email,$password);
        if($u!=false && $u instanceof User)
        {
            Application::$user = $u;
            $_SESSION["user"] = serialize($u);
            return $u;
        }

        return false;
    }

    public static function ping($ownerid,$userid,$msg){

    }

}