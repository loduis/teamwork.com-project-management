<?php
namespace TeamWorkPm;

class Me extends Rest\Model
{

    public function get()
    {
        return $this->_get("me");
    }
}