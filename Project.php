<?php
namespace TeamWorkPm;

class Project extends Model
{
    protected function _init()
    {
        $this->_fields = array(
            // New Project Name
            'name'        => true,
            // [Optional. Project Description]
            'description' =>  false,
            // [Optional. Start date in yyyymmdd format]
            'start_date'  => array(
                'required'=> false,
                'attributes' => array(
                    'type'=>'integer'
                )
            ),
            // [Optional. End date in yyyymmdd format]
            'end_date'    => array(
                'required' => false,
                'attributes' => array(
                    'type'=>'integer'
                )
            ),
            // [Optional. Id of company to assign the project to]
            'company_id'  => array(
                'required' => false,
                'attributes' => array(
                    'type' => 'integer'
                )
            ),
            // [Optional. Name of a new company to assign the project to]
            'new_company'    => false,
            //[Optional. Numeric ID of project category, 0 = no category]
            'category_id'     => false,

            'notifyeveryone' => array(
                'required' => false,
                'attributes' => array(
                    'type'=>'boolean'
                )
            ),
            'status'         => false
        );
    }

    /**
     * Retrieves all accessible projects; including active/inactive and archived projects.
     * You can optionally append a date to the call to return only those projects recently updated.
     * This is very useful if you are implementing local caching as you won't have to recheck
     * everything therefore making your applicaton much faster. You can pass in a date and/or a date
     * with a time using the variables updatedAfterDate and updatedAfterTime.
     * @return TeamWorkPm\Response\Model
     */
    public function getAll($params = array())
    {
        return $this->_getByStatus('all', $params);

    }

    /**
     *
     * @param type $date
     * @param type $time
     * @return TeamWorkPm\Response\Model
     */
    public function getActive($params = array())
    {
        return $this->_getByStatus('active', $params);
    }

    /**
     *
     * @param type $date
     * @param type $time
     * @return TeamWorkPm\Response\Model
     */
    public function getArchived($params = array())
    {
        return $this->_getByStatus('archived', $params);
    }

    /**
     *
     * @param type $status
     * @param type $date
     * @param type $time
     * @return type
     */
    private function _getByStatus($status, $params)
    {
        $params = (array) $params;
        $params['status'] = $status;
        return $this->rest->get("$this->_action", $params);
    }

    /**
     * Surprisingly, this will retrieve all of your projects, which have been starred!
     * @return array|SimpleXMLElement
     */
    public function getStarred()
    {
        return $this->rest->get("$this->_action/starred");
    }

    /**
     * Adds a project to your list of favourite projects.
     * @param int $id
     * @return bool
     */
    public function star($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->rest->put("$this->_action/$id/star");
    }

    /**
     * Removes a project from your list of favourite projects.
     * @param int $id
     * @return bool
     */
    public function unStar($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new \TeamWorkPm\Exception('Invalid param id');
        }
        return $this->rest->put("$this->_action/$id/unstar");
    }

    /**
     * Shortcut for active project
     *
     * @param type $id
     * @return bool
     */
    public function activate($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        $data = array();
        $data['id'] = $id;
        $data['status'] = 'active';
        return $this->update($data);
    }

    /**
     * Shortcut for archive project
     *
     * @param type $id
     * @return bool
     */
    public function archive($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        $data = array();
        $data['id'] = $id;
        $data['status'] = 'archived';
        return $this->update($data);
    }
}