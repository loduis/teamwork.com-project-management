<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Exception;
use TeamWorkPm\Rest\Response\Model as Response;
use TeamWorkPm\Rest\Resource\DestroyTrait;
use TeamWorkPm\Rest\Resource\GetTrait;
use TeamWorkPm\Rest\Resource\Project\ActionTrait as ProjectTrait;
use TeamWorkPm\Rest\Resource\SaveTrait;
use TeamWorkPm\Rest\Resource\UpdateTrait;
use TeamWorkPm\Rest\Resource;

class Role extends Resource
{
    protected ?string $action = 'roles';

    protected ?string $parent = 'role';

    protected string|array $fields = 'roles';

    use GetTrait, ProjectTrait, UpdateTrait, SaveTrait, DestroyTrait;

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
}
