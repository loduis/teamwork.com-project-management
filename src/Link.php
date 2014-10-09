<?php namespace TeamWorkPm;

class Link extends Model
{

    protected function init()
    {
        $this->fields = [
            // {link name}
            'name'          =>true,
            // {link display code: Embed code, Iframe code, URL}
            'code'          => true,
            // {link description}
            'description'   =>false,
            // {1|0}
            'private'       =>[
                'required'=> false,
                'type'=>'integer',
                'validate'=> [0, 1]
            ],
            // {width of window in Teamwork (integer)}
            'width'         => [
                'required'=> false,
                'type'=>'integer'
            ],
            // {height of window in Teamwork (integer)}
            'height'        => [
                'required'=> false,
                'type'=>'integer'
            ],
            // {link category id}
            'category_id'   => [
                'required'=> false,
                'type'=>'integer'
            ],
            // {New link category name. category-id must be passed as 0}
            'category_name' => false,
            // {Comma separated list of users to notify OR (YES|NO|ALL)}
            'notify'        => false,
            // {Force link to open in new window (boolean)}
            'open_in_new_window' => [
                'required'=> false,
                'type'=>'boolean'
            ]
        ];
    }

    /**
     * List All Links
     * GET /links
     * Lists all links on projects that the authenticated user is associated with.
     *
     */
    public function getAll()
    {
        return $this->rest->get($this->action);
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
    public function getByProject($project_id)
    {
        $project_id = (int) $project_id;
        if ($project_id <= 0) {
            throw new \TeamWorkPm\Exception('Invalid param project_id');
        }
        return $this->rest->get("/projects/$project_id/$this->action");
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
        $project_id = empty($data['project_id']) ? 0: (int) $data['project_id'];
        if ($project_id <= 0) {
            throw new Exception('Required field project_id');
        }
        return $this->rest->post("projects/$project_id/$this->action", $data);
    }
}
