<?php

include_once("classes/DB.php");

if($_SERVER["REQUEST_METHOD"]=="PUT"){
    parse_str(file_get_contents("php://input"),$vars);

}