<?php

namespace TeamWorkPm\Category;

use TeamWorkPm\Model;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/project-categories
 */
class Project extends Model
{
    protected ?string $parent = 'category';

    protected ?string $action = 'projectCategories';

    protected string|array $fields = 'resource_categories';
}
