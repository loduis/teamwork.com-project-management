<?php

namespace TeamWorkPm;

use TeamWorkPm\Response\Model as Response;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/notebooks/get-notebooks-json
 */
class Notebook extends Model
{
    protected ?string $parent = 'notebook';

    protected ?string $action = 'notebooks';

    protected string|array $fields = 'notebooks';

    /**
     * Get all Notebooks across all Projects
     *
     * @param array|object $params
     * @return Response
     * @throws Exception
     */
    public function all(array|object $params = []): Response
    {
        return $this->fetch("$this->action", $params);
    }

    /**
     * Get all Notebooks on a given Project
     *
     * @param int $id
     * @param array|object $params
     * @return Response
     * @throws Exception
     */
    public function getByProject(int $id, array|object $params = []): Response
    {
        return $this->fetch("projects/$id/$this->action", $params);
    }

    /**
     * Lock a Single Notebook For Editing
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function lock(int $id)
    {
        return $this->put("$this->action/$id/lock");
    }

    /**
     * Unlock a Single Notebook
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function unlock(int $id)
    {
        return $this->put("$this->action/$id/unlock");
    }

    /**
     * Create a Notebook on a Project
     *
     * @param array|object $data
     * @return int
     * @throws Exception
     */
    public function create(array|object $data): int
    {
        $data = arr_obj($data);

        $projectId = $data->pull('project_id');
        $this->validates([
            'project_id' => $projectId
        ], true);

        return $this->post("projects/$projectId/$this->action", $data);
    }

    /**
     * Copy a Notebook to another Project
     *
     * @param integer $id
     * @param integer $projectId
     * @return int
     */
    public function copy(int $id, int $projectId): int
    {
        return $this->notUseFields()
            ->put(
                "$this->action/$id/copy",
                compact('projectId')
        );
    }

    /**
     * Move a Notebook to another Project
     *
     * @param integer $id
     * @param integer $projectId
     * @param integer $taskListId
     * @return bool
     */
    public function move(int $id, int $projectId): bool
    {
        return $this->notUseFields()
            ->put(
                "$this->action/$id/move",
                compact('projectId')
        );
    }
}
