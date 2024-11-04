<?php

declare(strict_types = 1);

namespace TeamWorkPm\Task;

use TeamWorkPm\Exception;
use TeamWorkPm\Rest\Resource;
use TeamWorkPm\Response\Model as Response;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/files/get-tasks-id-files-json
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/files/post-tasks-id-files-json
 */
class File extends Resource
{
    protected ?string $parent = 'file';

    protected ?string $action = 'files';

    protected string|array $fields = 'tasks.files';

    /**
     * List Files on a Task
     *
     * @param integer $id
     * @return Response
     * @throws Exception
     */
    public function all(int $id): Response
    {
        return $this->fetch("tasks/$id/$this->action");
    }

    /**
     * Add a File to a Task
     *
     * @param integer $id
     * @param object|array $data
     * @return int
     * @throws Exception
     */
    public function add(int $id, object|array $data): int
    {
        return $this->post("tasks/$id/$this->action", $data);
    }
}