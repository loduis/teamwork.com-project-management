<?php namespace TeamWorkPm\Category;

class Project extends \TeamWorkPm\Model
{

    protected  function init()
    {
        list ($parent, $type) = explode('-', $this->parent);
        $this->parent = $parent;
        $this->action = $type . 'Categories';
        $this->fields = [
            'name'=>true,
            'parent'=> false
        ];
    }

    /**
     * Retrieve all Project Categories
     * GET /projectCategories.xml
     * Will return all project categories
     */
    public function getAll()
    {
        return $this->rest->get($this->action);
    }
}