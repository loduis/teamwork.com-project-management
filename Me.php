<?php

class TeamWorkPm_Me extends TeamWorkPm_Rest_Model
{

    public function get()
    {
        return $this->_get("$this->_action");
    }
}