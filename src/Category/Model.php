<?php

declare(strict_types = 1);

namespace TeamWorkPm\Category;

use TeamWorkPm\Exception;
use TeamWorkPm\Rest\Response\Model as Response;
use TeamWorkPm\Rest\Resource\DestroyTrait;
use TeamWorkPm\Rest\Resource\GetTrait;
use TeamWorkPm\Rest\Resource\Project\ActionTrait as ProjectTrait;
use TeamworkPm\Rest\Resource\SaveTrait;
use TeamworkPm\Rest\Resource\UpdateTrait;

abstract class Model extends \TeamWorkPm\Rest\Resource
{
    use GetTrait, ProjectTrait, UpdateTrait, SaveTrait, DestroyTrait;

    protected ?string $parent = 'category';

    protected string|array $fields = 'resource_categories';

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
