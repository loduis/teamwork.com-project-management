<?php

namespace TeamWorkPm\Category;

use TeamWorkPm\Model;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/project-categories
 */
class Project extends Model
{
    protected function init()
    {
        [$parent, $type] = explode('-', $this->parent);
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
    public function getAll()
    {
        return $this->rest->get($this->action);
    }
}
