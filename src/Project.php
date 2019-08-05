<?php

namespace TeamWorkPm;

class Project extends Model
{
    protected function init()
    {
        $this->fields = [
            // New Project Name
            'name'        => false,
            // [Optional. Project Description]
            'description' =>  false,
            // [Optional. Start date in yyyymmdd format]
            'startDate'  => [
                'required'=> false,
                'attributes' => [
                    'type'=>'integer'
                ]
            ],
            // [Optional. End date in yyyymmdd format]
            'endDate'    => [
                'required' => false,
                'attributes' => [
                    'type'=>'integer'
                ]
            ],
            // [Optional. Id of company to assign the project to]
            'companyId'  => [
                'required' => false,
                'attributes' => [
                    'type' => 'integer'
                ]
            ],
            // [Optional. Name of a new company to assign the project to]
            'newCompany'    => false,
            //[Optional. Numeric ID of project category, 0 = no category]
            'category-id'     => false,
            // [Optional. Comma separated list of tags to apply to project]
            'tags'            => false,

            'notifyeveryone' => [
                'required' => false,
                'attributes' => [
                    'type'=>'boolean'
                ]
            ], 
            'status'         => false,
            "use-tasks"      => false,
            "use-milestones" => false,
            "use-messages"   => false,
            "use-files"      => false,
            "use-time"       => false,
            "use-notebook"   => false,
            "use-riskregister" => false,
            "use-links"      => false, 
            "use-billing"    => false  
        ];
    }

    /**
     * Retrieves all accessible projects; including active/inactive and archived projects.
     * You can optionally append a date to the call to return only those projects recently updated.
     * This is very useful if you are implementing local caching as you won't have to recheck
     * everything therefore making your applicaton much faster. You can pass in a date and/or a date
     * with a time using the variables updatedAfterDate and updatedAfterTime.
     *
     * @param array $params
     *
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function getAll(array $params = [])
    {
        return $this->getByStatus('all', $params);
    }

    /**
     * @param array $params
     *
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function getActive(array $params = [])
    {
        return $this->getByStatus('active', $params);
    }

    /**
     * @param array $params
     *
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function getArchived(array $params = [])
    {
        return $this->getByStatus('archived', $params);
    }

    /**
     *
     * @param string $status
     * @param array $params
     *
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    private function getByStatus($status, $params)
    {
        $params = (array) $params;
        $params['status'] = strtoupper($status);
        return $this->rest->get("$this->action", $params);
    }

    /**
     * Surprisingly, this will retrieve all of your projects, which have been starred!
     *
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function getStarred()
    {
        return $this->rest->get("$this->action/starred");
    }

    /**
     * Adds a project to your list of favourite projects.
     *
     * @param int $id
     *
     * @return bool
     * @throws \TeamWorkPm\Exception
     */
    public function star($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->rest->put("$this->action/$id/star");
    }

    /**
     * Removes a project from your list of favourite projects.
     *
     * @param int $id
     *
     * @return bool
     * @throws \TeamWorkPm\Exception
     */
    public function unStar($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new \TeamWorkPm\Exception('Invalid param id');
        }
        return $this->rest->put("$this->action/$id/unstar");
    }

    /**
     * Shortcut for active project
     *
     * @param int $id
     *
     * @return bool
     * @throws \TeamWorkPm\Exception
     */
    public function activate($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        $data = [];
        $data['id'] = $id;
        $data['status'] = 'active';
        return $this->update($data);
    }

    /**
     * Shortcut for archive project
     *
     * @param int $id
     *
     * @return bool
     * @throws \TeamWorkPm\Exception
     */
    public function archive($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        $data = [];
        $data['id'] = $id;
        $data['status'] = 'archived';
        return $this->update($data);
    }
}
