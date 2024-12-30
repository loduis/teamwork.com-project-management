<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Resource\Model;
use TeamWorkPm\Rest\Response\Model as Response;
use TeamWorkPm\Rest\Resource\Project\GetByTrait as GetByProjectTrait;
use TeamWorkPm\Rest\Resource\Task\GetByTrait as GetByTaskTrait;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/time-tracking/get-time-entries-json
 */
class Time extends Model
{
    use GetByProjectTrait, GetByTaskTrait;

    protected ?string $parent = 'time-entry';

    protected ?string $action = 'time_entries';

    protected string|array $fields = 'time_entries';

    public function create(array|object $data): int
    {
        $data = arr_obj($data);
        $projectId = (int) $data->pull('project_id');
        $taskId = (int) $data->pull('task_id');
        if (!($taskId || $projectId)) {
            throw new Exception('Required field project_id or task_id');
        }

        if ($taskId && $projectId) {
            throw new Exception('Only one field project_id or task_id');
        }

        if (!($data->offsetExists('hours') || $data->offsetExists('minutes'))) {
            throw new Exception('Required field hours or minutes');
        }

        $path = "projects/$projectId";
        if ($taskId) {
            $path = "tasks/$taskId";
        }

        return $this->post("$path/$this->action", $data);
    }

    /**
     * Time Totals on a Project
     *
     * @param int $id
     * @param array|object $params
     * @return Response
     * @throws Exception
     */
    public function getTotal(array|object $params = []): Response
    {
        return $this->fetch("$this->action/total", $params);
    }

    /**
     * Estimated Time Totals on Projects
     *
     * @param array|object $params
     * @return Response
     * @throws Exception
     */
    public function getEstimated(array|object $params = []): Response
    {
        return $this->fetch("projects/estimatedtime/total", $params);
    }

    public function getTimers(array|object $params = []): Response
    {
        return $this->fetch('timers', $params);
    }
}
