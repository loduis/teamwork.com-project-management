<?php

namespace TeamWorkPm\Category;

use TeamWorkPm\Model;
use TeamWorkPm\Response\Model as Response;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/project-categories
 */
class Project extends Model
{
    protected ?string $parent = 'category';

    protected ?string $action = 'projectCategories';

    protected string|array $fields = 'resource_categories';

    /**
     * Retrieving all of a Project Categories
     *
     * @return Response
     */
    public function all(): Response
    {
        return $this->fetch((string) $this->action);
    }
}
