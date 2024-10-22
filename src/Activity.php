<?php

declare(strict_types = 1);

namespace TeamWorkPm;

class Activity extends Rest\Resource
{
    protected ?string $action = 'latestActivity';

    /**
     * List Latest Activity (across all projects)
     * GET /activity.xml
     * Lists the latest activity list new tasks etc. across all projects that the user has access to.
     *
     * @param int $maxItems
     *
     * @param null $onlyStarred
     *
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function all(array $params = [])
    {
        return $this->rest->get("$this->action", $params);
    }

    /**
     * List Latest Activity (for a project)
     * GET /projects/#{project_id}/activity
     * Lists the latest activity list new tasks etc. for a given project.
     *
     * @param int $id
     * @param array $params
     *
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function getByProject(int $id, array $params = [])
    {
        $id = (int)$id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }

        return $this->rest->get("projects/$id/$this->action", $params);
    }

    /**
     * Get Task Activity
     *
     * @param int $id
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function getByTask(int $id)
    {
        return $this->rest->get("tasks/$id/activity");
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws \TeamWorkPm\Exception
     */
    public function delete(int $id)
    {
        $id = (int)$id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->rest->delete("activity/$id");
    }
}
