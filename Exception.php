<?php

class TeamWorkPm_Exception extends ErrorException
{

    public function  __construct($message)
    {
        $this->message = $message;
    }
}