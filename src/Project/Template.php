<?php

declare(strict_types = 1);

namespace TeamWorkPm\Project;

use TeamWorkPm\Rest\Resource;
use TeamWorkPm\Rest\Resource\DestroyTrait;
use TeamWorkPm\Rest\Resource\StoreTrait;
use TeamWorkPm\Rest\Response\Model as Response;

class Template extends Resource
{
    use StoreTrait, DestroyTrait;

    protected ?string $parent = 'project';

    protected ?string $action = 'projects';

    protected string|array $fields = 'projects.template';

    /**
     *
     * @param object|array $params Optional query parameters
     * @return Response
     * @throws Exception
     */
    public function all(object|array $params = []): Response
    {
        return $this->fetch("$this->action/api/v3/projects/templates", $params);
    }

    /**
     * @param array|object $data
     * @return int
     */
    public function create(array|object $data): int
    {
        /**
         * @var int
         */
        return $this->post("$this->action/template", $data);
    }
}