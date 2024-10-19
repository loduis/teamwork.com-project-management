<?php

namespace TeamWorkPm;

class Me extends Rest\Resource
{
    /**
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function get()
    {
        return $this->rest->get('me');
    }
}
