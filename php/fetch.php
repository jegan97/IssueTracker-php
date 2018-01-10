<?php
/**
 * Created by PhpStorm.
 * User: JEGAN'S BEAST
 * Date: 1/1/2017
 * Time: 1:15 PM
 */

include_once("classes/DB.php");

if($_SERVER["REQUEST_METHOD"]=="GET"){


    if(isset($_GET["users"])){

        $u = $_GET["users"];

        if($u==1){

            $res["members"] = DB::getDB()->getAllUsers();
            echo json_encode($res);

        }

    }
    else if(isset($_GET["events"])){

        $e  = $_GET["events"];

        if(isset($_GET["search"]))
            $search = $_GET["search"];
        else
            $search = "";

        if(isset($_GET["offset"]))
            $off = $_GET["offset"];
        else
            $off = 0;

        switch($e){
            case 0:
                $res["events"] = DB::getDB()->getEvents(Application::getLoggedInUserID(),false,$search,$off);
                echo json_encode($res);
                break;
            case 1:
                $res["events"] = DB::getDB()->getEvents(Application::getLoggedInUserID(),true,$search,$off);
                echo json_encode($res);
                break;
        }

    }



}