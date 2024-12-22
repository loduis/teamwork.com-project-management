<?php

declare(strict_types = 1);

namespace TeamWorkPm\Rest\Resource;

use TeamWorkPm\Rest\Response\Model as Response;

trait GetAllTrait
{
    /**
     *
     * @param object|array $params Optional query parameters
     * @return Response
     * @throws Exception
     */
    public function all(object|array $params = []): Response
    {
        return $this->fetch("$this->action", $params);
    }
}
