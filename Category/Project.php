<?php
namespace TeamWorkPm\Category;

class Project extends \TeamWorkPm\Model
{

    protected  function _init()
    {
        list ($parent, $type) = explode('-', $this->_parent);
        $this->_parent = $parent;
        $this->_action = $type . 'Categories';
        $this->_fields = array(
            'name'=>true,
            'parent'=> false
        );
    }

    /**
     * Retrieve all Project Categories
     * GET /projectCategories.xml
     * Will return all project categories
     */
    public function getAll()
    {
        return $this->rest->get($this->_action);
    }
}