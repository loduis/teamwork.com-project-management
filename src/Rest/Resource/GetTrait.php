<?php

namespace TeamWorkPm\Rest\Resource;

use TeamWorkPm\Rest\Response\Model as Response;

trait GetTrait
{
    /**
     * @param int $id
     * @param object|array|null $params
     *
     * @return Response
     * @throws Exception
     */
    public function get(int $id, object|array|null $params = null): Response
    {
        $this->validates(['id' => $id]);

        return $this->fetch("$this->action/$id", $params);
    }
}