<?php

namespace TeamWorkPm\Category;

use TeamWorkPm\Model;
use TeamWorkPm\Response\Model as Response;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/project-categories
 */
class Project extends Model
{
    protected string|array $fields = 'resource_categories';

    protected function init()
    {
        [$this->parent, $type] = explode('-', (string) $this->parent);
        $this->action = $type . 'Categories';
    }

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
