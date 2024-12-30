<?php

declare(strict_types = 1);

namespace TeamWorkPm\Rest\Resource\Task;

use TeamWorkPm\Rest\Response\Model as Response;

trait GetByTrait
{
    /**
     * Get all Resource on a given Project
     *
     * @param int $id
     * @param array|object $params
     * @return Response
     * @throws Exception
     */
    public function getByTask(int $id, array|object $params = []): Response
    {
        return $this->fetch("tasks/$id/$this->action", $params);
    }
}
