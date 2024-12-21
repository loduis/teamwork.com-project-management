<?php

declare(strict_types = 1);

namespace TeamWorkPm\Rest;

trait ProjectTrait
{

    use GetByProjectTrait;

    /**
     * Create a Resource on a Project
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
}