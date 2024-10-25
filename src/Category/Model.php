<?php

namespace TeamWorkPm\Category;

use TeamWorkPm\Exception;

abstract class Model extends \TeamWorkPm\Model
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
     * Retrieving all of a Projects Categories
     *
     * GET /projects/#{project_id}/#{resource}Categories.xml
     *
     * All the message categories for your project will be returned.
     *
     * @param int $project_id
     * @return \TeamWorkPm\Response\Model
     */
    public function getByProject(int $project_id)
    {
        if ($project_id <= 0) {
            throw new Exception('Invalid param project_id');
        }
        return $this->rest->get("projects/$project_id/$this->action");
    }

    /**
     * Creating Categories
     *
     * POST /projects/#{project_id}/#{resource}Categories.xml
     *
     * A new category will be created and attached to your specified project ID.
     *
     * @param array|object $data
     * @return int
     */
    public function insert(array|object $data): int
    {
        $data = arr_obj($data);
        $project_id = (int) ($data['project_id'] ?? 0);
        if ($project_id <= 0) {
            throw new Exception('Required field project_id');
        }
        /**
         * @var int
         */
        return $this->rest->post("projects/$project_id/$this->action", $data);
    }
}
