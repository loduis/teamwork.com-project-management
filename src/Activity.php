<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Response\Model as Response;
use TeamWorkPm\Rest\Resource\GetAllTrait;
use TeamWorkPm\Rest\Resource\Project\GetByTrait as GetByProjectTrait;

class Activity extends Rest\Resource
{
    use GetByProjectTrait, GetAllTrait;

    protected ?string $action = 'latestActivity';

    /**
     * Get Task Activity
     *
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function getByTask(int $id): Response
    {
        return $this->fetch("tasks/$id/activity");
    }

    /**
     * Delete an Activity Entry
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        return $this->del("activity/$id");
    }
}
