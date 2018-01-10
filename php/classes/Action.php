<?php

/**
 * Created by PhpStorm.
 * User: JEGAN'S BEAST
 * Date: 12/15/2016
 * Time: 3:33 PM
 */
class Action
{

    /**
     * @var int $actionid
     * @var int $userid
     * @var int $eventid
     * @var string $status
     * @var string $action
     * @var int $status_code
     * @var string $status_level
     **/
    public $actionid;
    public $userid;
    public $eventid;
    public $status;
    public $action;
    public $status_code;
    public $status_level;

    /**
     * @return int
     */
    public function getActionid()
    {
        return $this->actionid;
    }

    /**
     * @param int $actionid
     */
    public function setActionid($actionid)
    {
        $this->actionid = $actionid;
    }

    /**
     * @return int
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * @param int $userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
    }

    /**
     * @return int
     */
    public function getEventid()
    {
        return $this->eventid;
    }

    /**
     * @param int $eventid
     */
    public function setEventid($eventid)
    {
        $this->eventid = $eventid;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * @param int $status_code
     */
    public function setStatusCode($status_code)
    {
        $this->status_code = $status_code;
    }

    /**
     * @return string
     */
    public function getStatusLevel()
    {
        return $this->status_level;
    }

    /**
     * @param string $status_level
     */
    public function setStatusLevel($status_level)
    {
        $this->status_level = $status_level;
    }


}