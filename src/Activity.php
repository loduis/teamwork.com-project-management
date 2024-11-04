<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Response\Model as Response;

class Activity extends Rest\Resource
{
    protected ?string $action = 'latestActivity';

    /**
     * Latest Activity across all Projects
     *
     * @param object|array $params
     * @return Response
     * @throws Exception
     */
    public function all(object|array $params = []): Response
    {
        return $this->fetch("$this->action", $params);
    }

    /**
     * List Latest Activity for a Specific Project
     *
     * @param int $id
     * @param array $params
     * @return Response
     * @throws Exception
     */
    public function getByProject(int $id, array $params = []): Response
    {
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }

        return $this->fetch("projects/$id/$this->action", $params);
    }

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
