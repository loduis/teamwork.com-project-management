<?php

declare(strict_types = 1);

namespace TeamWorkPm\Project;

use TeamWorkPm\Exception;
use TeamWorkPm\Response\Model as Response;
use function TeamWorkPm\array_reduce;

use TeamWorkPm\Rest\Resource;

class Rate extends Resource
{
    protected ?string $parent = 'rates';

    protected ?string $actions = 'rates';

    protected string|array $fields = 'projects.rates';

    /**
     * @param int $projectId
     * @param object|array $params
     *
     * @return Response
     * @throws Exception
     */
    public function get(int $projectId, object|array $params = [])
    {
        return $this->fetch("projects/$projectId/$this->actions", $params);
    }

    /**
     * @param int $projectId
     * @param object|array $data
     *
     * @return bool
     * @throws Exception
     */
    public function set(int $projectId, object|array $data)
    {
        return $this->post("projects/$projectId/$this->actions", $data);
    }
}
