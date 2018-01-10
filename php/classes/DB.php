<?php

/**
 * Created by PhpStorm.
 * User: JEGAN'S BEAST
 * Date: 12/10/2016
 * Time: 7:16 PM
 */
include_once("Event.php");
include_once("User.php");
include_once("Response.php");
include_once("Application.php");


class DB
{

    /** @var DB $db**/
    private static $db =null;

    /** @var mysqli $conn**/
    private $conn;

    /** @var int $ROW_LIMIT **/
    private $ROW_LIMIT = 10;

    private function __construct()
    {
        $this->conn = new mysqli("localhost","root","root","it");
    }

    public static function getDB(){
        if(DB::$db == null)
            DB::$db  = new DB();
        return DB::$db ;

    }

    private function open(){
        if( !isset($this->conn) || $this->conn==null)
            $this->conn = new mysqli("localhost","root","root","it");
    }

    /**
     * @param int $userid
     * @return bool|User
     */
    public function getUserDetails($userid){
        $this->open();

        $query = $this->conn->prepare("select user_id,username,email,user_position,department,department_name,user_level from users JOIN departments on users.department = departments.dept_id where user_id=?");
        $query->bind_param('i',$userid);
        $query->execute();
        $query->store_result();

        if($query->num_rows == 1)
        {
            $query->bind_result($uid,$username,$mail,$position,$department,$dept_name,$level);
            $query->fetch();

            $u = new User();
            $u->setUserid($uid);
            $u->setUsername($username);
            $u->setEmail($mail);
            $u->setLevel($level);
            $u->setDepartment($department,$dept_name);
            $u->setPosition($position);

            return $u;
        }

        return false;

    }

    /**
     * @param int $userid
     * @param bool $invited
     * @param string $search
     * @param int $offset
     * @return Event[]
     */
    public function getEvents($userid=null,$invited=false,$search = "",$offset=-1){

        $this->open();

        if(!isset($userid)){

            $sql = "select event_id,owner_id,event_name,description,status_code,start_date,due_date from events WHERE event_name LIKE CONCAT('%',?,'%')";

        }
        else if($invited == false){
            $sql = "select event_id,owner_id,event_name,description,status_code,start_date,due_date from events where owner_id=? AND event_name LIKE CONCAT('%',?,'%')";
        }
        else{
            $sql = "select event_id,owner_id,event_name,description,status_code,start_date,due_date from events where event_id in (select event_id from usergroup where user_id = ?) AND event_name LIKE CONCAT('%',?,'%')";
        }

        if($offset!=-1)
            $sql .= " LIMIT ".$this->ROW_LIMIT." OFFSET ".($offset*$this->ROW_LIMIT);

        $stmt = $this->conn->prepare($sql);

        if(isset($userid))
             $stmt->bind_param('is',$userid,$search);
        else
            $stmt->bind_param('s',$search);


        $stmt->execute();

        $stmt->store_result();

        $stmt->bind_result($id,$ownerid,$name,$description,$status_code,$start_date,$due_date);

        $events = [];

        while($stmt->fetch()){

            $e = new Event();
            $e->setEventid($id);
            $e->setDescription($description);
            $e->setName($name);
            $e->setStatusCode($status_code);
            $e->setStartDate($start_date);
            $e->setDueDate($due_date);
            $e->setOwnerid($ownerid);

            $members = $this->conn->prepare("select user_id from usergroup where event_id=?");
            $members->bind_param('i',$id);
            $members->execute();
            $members->store_result();
            $members->bind_result($memberid);

            $memids = [];

            while($members->fetch()){
                array_push($memids,$memberid);
            }

            $e->setMembersFromDb($memids);


            $actions = $this->conn->prepare("select action_id from actions where event_id = ?");

            $actions->bind_param('i',$id);
            $actions->execute();
            $actions->store_result();
            $actions->bind_result($actionid);

            $actids = [];

            while($actions->fetch()){
                array_push($actids,$actionid);
            }

            $e->setActionids($actids);

            $departments = $this->conn->prepare("select dept_id from departmentgroup where event_id = ?");

            $departments->bind_param('i',$id);
            $departments->execute();
            $departments->store_result();
            $departments->bind_result($deptid);

            $deptids = [];

            while($departments->fetch()){
                array_push($deptids,$deptid);
            }

            $e->setDepartmentFromDB($deptids);


            array_push($events,$e);

        }

        return $events;

    }

    /**
     * @return User[]
     */

    public function getAllUsers(){

        $this->open();

        $u = [];

        $st = $this->conn->prepare("select user_id,username,email,short_name,department_name,user_position,user_level from users JOIN departments on users.department = departments.dept_id WHERE user_id <> ?");

        $id = Application::getLoggedInUserID();

        $st->bind_param('i',$id);

        $st->execute();

        $st->store_result();

        $st->bind_result($uid,$name,$mail,$dept,$department_name,$pos,$lvl);

        while($st->fetch()){

            $usr = new User();

            $usr->setUserid($uid);
            $usr->setDepartment($dept,$department_name);
            $usr->setEmail($mail);
            $usr->setUsername($name);
            $usr->setPosition($pos);
            $usr->setLevel($lvl);

            array_push($u,$usr);
        }

        return $u;

    }

    /**
     * @param Event $event
     * @return bool
     */

    public function addEvent($event,$actions){

        $event->setStatusCode(0);

        $qry = $this->conn->prepare("insert into events(owner_id,event_name,description,status_code,start_date,due_date) VALUES (?,?,?,?,?,?)");

        $uid = Application::getLoggedInUserID();
        $name = $event->getName();
        $des = $event->getDescription();
        $status = $event->getStatusCode();
        $start = $event->getStartDate();
        $due = $event->getDueDate();


        $qry->bind_param('ississ',
            $uid,
            $name,
            $des,
            $status,
            $start,
            $due
            );

       if( $qry->execute()) {

           $eventid = $qry->insert_id;

           $memids = $event->getMemberIDs();

           for($i=0;$i<count($memids);$i++){

               $memberid = $memids[$i];

               $mem = $this->conn->prepare("insert into usergroup(user_id,event_id) VALUES(?,?) ");
               $mem->bind_param('ii',$memberid,$eventid);
               $mem->execute();
           }

           $depts = $event->getDepartmentIds();

           for($i=0;$i<count($depts);$i++){

               $deptid = $depts[$i];

               $d = $this->conn->prepare("insert into departmentgroup(dept_id,event_id) VALUES(?,?) ");
               $d->bind_param('ii',$deptid,$eventid);
               $d->execute();

           }

           for($i=0;$i<count($actions);$i++){

               $userid = $actions[$i]["user"]["userid"];
               $action = $actions[$i]["task"];

               $d = $this->conn->prepare("insert into actions(user_id,action,event_id) VALUES(?,?,?) ");
               $d->bind_param('isi',$userid,$action,$eventid);
               $d->execute();

           }


           return true;
       }

        return false;
    }

    /**
     * @param Event $event
     */
    public function deleteEvent($event){

    }


    public function getActions($eventid){



    }

    /**
     * @param string $email
     * @param string $pass
     * @return bool|User
     */
    public function isUserValid($email, $pass){

        $this->open();

        $email = $this->makeSafe($email);
        $pass = $this->makeSafe($pass);


        $stmt = $this->conn->prepare("select user_id,username,email,user_position,department,department_name,user_level from users JOIN departments on users.department = departments.dept_id where email=? AND password=?");

        $stmt->bind_param("ss",$email,$pass);

        $stmt->execute();

        $stmt->store_result();

        if($stmt->num_rows == 1){
            $stmt->bind_result($uid,$username,$mail,$position,$department,$dep_name,$level);
            $stmt->fetch();

            $u = new User();
            $u->setUserid($uid);
            $u->setUsername($username);
            $u->setEmail($email);
            $u->setLevel($level);
            $u->setDepartment($department,$dep_name);
            $u->setPosition($position);

            return $u;
        }
        else{
            return false;
        }

    }

    /**
     * @param int $departmentid
     * @return string
     */
    function getDepartmentName($departmentid){

        $ret = "";

        $this->open();

        $stat = $this->conn->prepare("select department_name from departments where dept_id=?");

        $stat->bind_param('i',$departmentid);

        $stat->execute();

        $stat->store_result();

        $stat->bind_result($dept_name);

        $stat->fetch();

        if($stat->affected_rows == 1) {
            $ret = $dept_name;

        }
            return $ret;

    }

    /**
     * @param int $actionid
     * @return Action|null
     */
    public function getActionDetails($actionid){

        $this->open();

        $stat = $this->conn->prepare("select user_id,action,status,actions.status_code,status_level,event_id from actions JOIN action_status on actions.status_code = action_status.status_code  where action_id=?");

        $stat->bind_param('i',$actionid);

        $stat->execute();

        $stat->store_result();

        $stat->bind_result($userid,$action,$status,$statusCode,$statusLevel,$eventid);

        $stat->fetch();

        if($stat->affected_rows == 1) {
            $a = new Action();
            $a->setUserid($userid);
            $a->setStatus($status);
            $a->setAction($action);
            $a->setEventid($eventid);
            $a->setStatusLevel($statusLevel);
            $a->setStatusCode($statusCode);

            return $a;
        }

        return null;
    }

    private function makeSafe($var){
        $var = trim($var);
        $var = stripslashes($var);

        return $var;
    }
    //other DB functions

}