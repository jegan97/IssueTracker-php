<?php

/**
 * Created by PhpStorm.
 * User: JEGAN'S BEAST
 * Date: 12/10/2016
 * Time: 7:05 PM
 */

include_once("DB.php");

class User
{

       public $userid;
    public $username;
    public $email;
    public $position;
    public  $department;
    public $department_name;
    public $level;

    public function getHigherLevelUsers(){

        $users = [];

        for($i=0;$i<2;$i++){

            $u = new User();

            $u->setUserid($i);
            $u->setEmail("user".$i . "@gmail.com");
            $u->setUsername("user".$i);
            $u->setLevel(1);

            array_push($users,$u);

        }

        return $users;
    }


    public function getJSON(){

        return json_encode($this);

    }


    /**
     * @return Event[]
     */
    public function getCreatedEvents(){

        $e = new Event();



    }


    /**
     * @return Event[]
     */
    public function getAssignedEvents(){


    }


    public function getUserid()
    {
        return $this->userid;
    }


    public function setUserid($userid)
    {
        $this->userid = $userid;
    }


    public function getUsername()
    {
        return $this->username;
    }


    public function setUsername($username)
    {
        $this->username = $username;
    }


    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }


    public function getLevel()
    {
        return $this->level;
    }


    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param string $department
     */
    public function setDepartment($department,$department_name)
    {
        $this->department = $department;
        $this->department_name = $department_name;
    }

}