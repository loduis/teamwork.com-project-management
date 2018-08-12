<?php

namespace TeamWorkPm;

class Me extends Rest\Model
{

    /**
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function get()
    {
        return $this->rest->get("me");
    }
}
