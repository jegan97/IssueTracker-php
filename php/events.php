<?php
/**
 * Created by PhpStorm.
 * User: JEGAN'S BEAST
 * Date: 12/15/2016
 * Time: 2:39 PM
 */
date_default_timezone_set('Asia/Kolkata');


include_once("classes/Event.php");
include_once("classes/DB.php");

class EventsResponse{

    /** @var Event[] $events**/
    private $events;
    private $invited;

    /**
     * EventsResponse constructor.
     * @param $invited
     * @internal param $userid
     */

    public function __construct($invited)
    {
        $this->invited = $invited;

        if($invited==true)
            $this->events = Application::getLoggedInUser()->getAssignedEvents();
        else
            $this->events = Application::getLoggedInUser()->getCreatedEvents();
    }

    /**
     * @return Event[]
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @return string
     */
    public function getJSON(){

        $res["events"]=$this->events;

        return json_encode($res);

    }

}


if($_SERVER["REQUEST_METHOD"]=="GET"){

    $invited = $_GET["invited"];

    $res = new EventsResponse($invited);

    echo $res->getJSON();

}

if($_SERVER["REQUEST_METHOD"]=="POST"){


    $event = json_decode(file_get_contents('php://input'),true);

    $e = new Event();

    $e->setName($event["subject"]);
    $e->setDescription($event["description"]);
    $e->setStartDate(formatToDate($event["start_date"]));
    $e->setDueDate(formatToDate($event["due_date"]));
    $e->setMembers($event["members"]);
    $e->setDepartment($event["department"]);
    $ret = DB::getDB()->addEvent($e,$event["members"]);

    $res["success"] = $ret;

    echo json_encode($res);

}


function formatToDate($date){
   $datetime=  DateTime::createFromFormat('d/m/Y',$date);
    $now =  new DateTime();
    if($datetime!=false)
        return $datetime->format("Y-m-d H:i:s");
    else
        return $now->format("Y-m-d H:i:s");
}