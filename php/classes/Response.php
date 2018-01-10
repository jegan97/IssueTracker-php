<?php

/**
 * Created by PhpStorm.
 * User: JEGAN'S BEAST
 * Date: 12/10/2016
 * Time: 7:40 PM
 */




class Response
{

    const SUCCESS = 1;
    const FAILURE = 0;

    protected $status;
    protected $error;

    public function __construct()
    {
        $this->status = Response::FAILURE;
        $this->error = "";
    }


    public function getStatus()
    {
        return $this->status;
    }


    public function setStatus($status)
    {
        $this->status = $status;
    }


    public function getError()
    {
        return $this->error;
    }
    public function setError($error)
    {
        $this->error = $error;
    }

}