<?php

/**
 * Created by PhpStorm.
 * User: JEGAN'S BEAST
 * Date: 12/10/2016
 * Time: 7:05 PM
 */
include_once("User.php");
include_once("Action.php");

class Event
{

    /**
        @var int $eventid, string $name, string $description, Date $start_date, Date $due_date ,int $status_code,User[] $members,string[] $department
     * @var boolean $edit
     * @var boolean $has_action
     * @var Action $your_action
     * @var Action[] $actions
     **/
    public $eventid;
    public $name;
    public $description;
    public $start_date;
    public $due_date;
    public $status_code;
    public $members;
    public $ownerid;
    public $memids;
    public $department;
    public $departmentids;
    public $edit;
    public $has_action=false;
    public $your_action;
    public $actions;
    private $actionids;
    /**
 * @return mixed
 */
    public function getEventid()
    {
        return $this->eventid;
    }

    /**
     * @param mixed $eventid
     */
    public function setEventid($eventid)
    {
        $this->eventid = $eventid;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @param mixed $start_date
     */
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;
    }

    /**
     * @return mixed
     */
    public function getDueDate()
    {
        return $this->due_date;
    }

    /**
     * @param mixed $due_date
     */
    public function setDueDate($due_date)
    {
        $this->due_date = $due_date;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * @param mixed $status_code
     */
    public function setStatusCode($status_code)
    {
        $this->status_code = $status_code;
    }

    public function setMembersFromDb($memberids){

        $this->memids = $memberids;
        $this->members = array();

        for($i=0;$i<count($memberids);$i++){
            $u = DB::getDB()->getUserDetails($memberids[$i]);
            array_push($this->members,$u);
        }

    }

    public function setMembers($members){

        $this->memids = array();

        for($i=0;$i<count($members);$i++){
            $id = $members[$i]["user"]["userid"];
            array_push($this->memids,$id);
        }

        $this->members = array();

        for($i=0;$i<count($members);$i++){
            $u = $members[$i]["user"];
            array_push($this->members,$u);
        }

    }

    public function getMemberIDs(){
        return $this->memids;
    }

    /**
     * @return User[]
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @return int
     */
    public function getOwnerid()
    {
        return $this->ownerid;
    }

    /**
     * @param int $ownerid
     */
    public function setOwnerid($ownerid)
    {
        $this->ownerid = $ownerid;

        if($ownerid == Application::getLoggedInUserID()){
            $this->edit = true;
        }
        else
            $this->edit = false;

    }

    /**
     * @return string[]
     */
    public function getDepartments()
    {
        return $this->department;
    }

    /**
     * @return int[]
     */
    public function getDepartmentIds()
    {
        return $this->departmentids;
    }


    public function setDepartmentFromDB($departmentids)
    {
        $this->department = [];
        $this->departmentids=$departmentids;

        for($i =0;$i<count($departmentids);$i++){
            $id = $departmentids[$i];

            $d = DB::getDB()->getDepartmentName($id);
            array_push($this->department,$d);
        }

    }


    /**
     * @param int[] $departmentids
     */
    public function setDepartment($departmentids)
    {
        $this->department = [];
        $this->departmentids=[];
        for($i =0;$i<count($departmentids);$i++){
            $d = $departmentids[$i];
            array_push($this->departmentids,$d["id"]);
            array_push($this->department,$d["name"]);
        }

    }


    /**
     * @param Action[] $actions
     */
    public function setActions($actions){

            for($i=0;$i<count($actions);$i++){

                /** @var Action $a */
                $a = $actions[$i];

                if($a->getUserid() == Application::getLoggedInUserID()) {
                    $this->has_action = true;
                    $this->your_action = $a;
                }
            }

        $this->actions = $actions;
    }

    /**
     * @return Action[]
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @return mixed
     */
    public function getActionids()
    {
        return $this->actionids;
    }

    /**
     * @param mixed $actionids
     */
    public function setActionids($actionids)
    {
        $this->actionids = $actionids;

        $this->actions = array();

        for($i =0;$i<count($actionids);$i++){
            $a = DB::getDB()->getActionDetails($actionids[$i]);
            if($a->getUserid() == Application::getLoggedInUserID()) {
                $this->has_action = true;
                $this->your_action = $a;
            }
            array_push($this->actions,$a);
        }
    }

    private function formatTime($d){
        $date = DateTime::createFromFormat("",$d);
        return $date->format("");
    }
}