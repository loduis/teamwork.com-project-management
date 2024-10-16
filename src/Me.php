<?php

namespace TeamWorkPm;

use TeamWorkPm\Response\Model;

class Me extends Rest\Resource
{
    /**
     * @return Model
     * @throws Exception
     */
    public function get()
    {
        return $this->rest->get('me');
    }
}
