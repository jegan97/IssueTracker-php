<?php
/**
 * Created by PhpStorm.
 * User: JEGAN'S BEAST
 * Date: 12/15/2016
 * Time: 4:06 PM
 */
include_once("classes/Application.php");


if($_SERVER["REQUEST_METHOD"]=="POST"){

    Application::logout();

}