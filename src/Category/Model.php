<?php

namespace TeamWorkPm\Category;

use TeamWorkPm\Exception;
use TeamWorkPm\Response\Model as Response;
use TeamWorkPm\Rest\ProjectTrait;
use TeamWorkPm\Rest\ResourceTrait;

abstract class Model extends \TeamWorkPm\Rest\Resource
{
    use ProjectTrait, ResourceTrait {
        ProjectTrait::create insteadof ResourceTrait;
    }

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
