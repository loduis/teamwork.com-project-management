<?php

namespace TeamWorkPm\Category;

use TeamWorkPm\Model;
use TeamWorkPm\Response\Model as ResponseModel;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/project-categories
 */
class Project extends Model
{
    protected function init()
    {
        [$parent, $type] = explode('-', (string) $this->parent);
        $this->parent = $parent;
        $this->action = $type . 'Categories';
        $this->fields = [
            'name' => true,
            'parent' => false,
        ];
    }

    /**
     * Retrieve all Project Categories
     * GET /projectCategories
     * Will return all project categories
     */
    public function all(): ResponseModel
    {
        return $this->rest->get((string) $this->action);
    }
}
