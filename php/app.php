<?php
/**
 * Created by PhpStorm.
 * User: JEGAN'S BEAST
 * Date: 12/15/2016
 * Time: 2:26 PM
 */

include_once("classes/Application.php");


if($_SERVER["REQUEST_METHOD"]=="GET"){

    $res["loggedin"] = false;

    if(Application::isLoggedIn()){
        $res["loggedin"] = true;
        $res["user"] = Application::getLoggedInUser();
    }

    echo json_encode($res);

}