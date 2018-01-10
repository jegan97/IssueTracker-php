<?php
/**
 * Created by PhpStorm.
 * User: JEGAN'S BEAST
 * Date: 12/10/2016
 * Time: 7:03 PM
 */

include_once("classes/Response.php");
include_once("classes/User.php");
include_once("classes/Application.php");


class LoginResponse extends Response{

    /**@var User**/
    private $user;


    public function __construct(){
        parent::__construct();
    }

    public function setUser(User $user){
        $this->user = $user;
    }

    public function setStatus($status){
        parent::setStatus($status);
    }

    public function setError($err){
        parent::setError($err);
    }

    public function getJSON(){

        $res["status"] = parent::getStatus();
        $res["user"] = $this->user;
        $res["error"] = parent::getError();

       return json_encode($res);

    }

}

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $email = trim($_POST["email"]);
    $pass = trim($_POST["password"]);

    $res = new LoginResponse();

    $user = Application::login($email,$pass);

    if($user!=false && $user instanceof User){
        $res->setStatus(Response::SUCCESS);
        $res->setError("");
        $res->setUser($user);
    }
    else{
        $res->setStatus(Response::FAILURE);
        $res->setError("email and password mismatched");
    }
    echo $res->getJSON();

}