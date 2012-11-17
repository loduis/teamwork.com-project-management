<?php
namespace TeamWorkPm;

class Link extends Model
{

    protected function _init()
    {
        $this->_fields = array(
            'name'=>true,
            'description'=>false,
            'private'=>false,
            'code'=>true,
            'width'=>false,
            'height'=>false,
            'category_id'=>false,
            'category_name'=>false,
            'notify'=>false,
            'open_in_new_window'=>false
        );
    }

    /**
     * List All Links
     * GET /links
     * Lists all links on projects that the authenticated user is associated with.
     *
     */
    public function getAll()
    {
        return $this->_get($this->_action);
    }

    /**
     * List Links on a Project
     *
     * GET /projects/#{project_id}/links
     *
     * This lets you query the list of links for a project.
     *
     * @param int $id This is a project id
     *
     * @return TeamWorkPm\Response\Model
     */
    public function getByProject($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new \TeamWorkPm\Exception('Require param id');
        }

        $response = $this->_get("/projects/$id/$this->_action");

        return $response;
    }

    /**
     * Create a Single Link
     *
     * POST /projects/#{project_id}/links
     * This command will create a single link.
     * Code must be valid Embed Code, IFrame Code or a URL
     * @param array $data
     *
     * @return int
     */
    public function insert(array $data)
    {
        $project_id = (int) empty($data['project_id']) ? 0 : $data['project_id'];
        if ($project_id <= 0) {
            throw new Exception('Require field project id');
        }
        return $this->_post("projects/$project_id/$this->_action", $data);
    }
}
