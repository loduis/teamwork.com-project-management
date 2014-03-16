<?php namespace TeamWorkPm\Category;

abstract class Model extends \TeamWorkPm\Model
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
     * Retrieving all of a Projects Categories
     *
     * GET /projects/#{project_id}/#{resource}Categories.xml
     *
     * All the message categories for your project will be returned.
     *
     * @param int $id
     * @return TeamWorkPm\Response\Model
     */
    public function getByProject($project_id)
    {
        $project_id = (int) $project_id;
        if ($project_id <= 0) {
            throw new \TeamWorkPm\Exception('Invalid param project_id');
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
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        $project_id = empty($data['project_id']) ? 0: (int) $data['project_id'];
        if ($project_id <= 0) {
            throw new \TeamWorkPm\Exception('Required field project_id');
        }
        return $this->rest->post("projects/$project_id/$this->action", $data);
    }
}