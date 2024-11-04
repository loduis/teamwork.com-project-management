<?php

namespace TeamWorkPm\Category;

use TeamWorkPm\Exception;
use TeamWorkPm\Response\Model as Response;

abstract class Model extends \TeamWorkPm\Model
{
    protected string|array $fields = 'resource_categories';

    protected function init()
    {
        [$this->parent, $type] = explode('-', (string) $this->parent);
        $this->action = $type . 'Categories';
    }

    /**
     * Retrieving all of a [File|Link|Message|Notebook] Categories by projects
     *
     * @param integer $id
     * @return Response
     */
    public function getByProject(int $id): Response
    {
        return $this->fetch("projects/$id/$this->action");
    }

    /**
     * Alias to getByProjectId
     *
     * @param integer $projectId
     * @throws Exception
     */
    public function all(int $projectId): Response
    {
        return $this->getByProject($projectId);
    }

    /**
     * Creating [File|Link|Message|Notebook] Categories
     *
     * @param array|object $data
     * @return integer
     * @return Response
     */
    public function create(array|object $data): int
    {
        $data = arr_obj($data);
        $projectId = (int) $data->pull('project_id');
        $this->validates([
            'project_id' => $projectId
        ]);
        /**
         * @var int
         */
        return $this->post("projects/$projectId/$this->action", $data);
    }
}
