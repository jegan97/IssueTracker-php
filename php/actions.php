<?php
/**
 * Created by PhpStorm.
 * User: JEGAN'S BEAST
 * Date: 12/15/2016
 * Time: 3:40 PM
 */

include_once("classes/Action.php");

class ActionUtils{

    private static $events;

    public static function getActionsAsJSON($eventid){

        ActionUtils::$events  = DB::getDB()->getActions($eventid);


    }


}