<?php

namespace TeamWorkPm;

class Task_List extends Model
{
    protected function _init()
    {
        $this->_fields = array(
            'name'   => true,
            'private' => array(
                'required'=>false,
                'attributes'=>array(
                    'type'=>'boolean'
                )
            ),
            'pinned'   =>array(
                'required'=>false,
                'attributes'=>array(
                    'type'=>'boolean'
                )
            ),
            'tracked' => array(
                'required'=>false,
                'attributes'=>array(
                    'type'=>'boolean'
                )
            ),
            'description'           => false,
            'milestone_id'          => array(
                'required'=>false,
                'attributes'=>array(
                    'type'=>'integer'
                )
            ),
            'todo_list_template_id' => false
        );
        $this->_parent = 'todo-list';
        $this->_action = 'todo_lists';
    }

    /**
     * Retrieve all lists in a Project
     *
     * GET /projects/#{project_id}/todo_lists.xml?filter=#{filter}
     *
     * Retrieves all todo lists in a project. You can further filter these results with the
     * 'filter' query. You can set this to 'all', 'pending', 'late' and 'finished'. 'pending'
     * lists incomplete tasks. The filter is defaulted to 'pending'
     *
     * @param <type> $id
     * @param <type> $filter
     * @return object
     */
    public function getByProject($project_id, $params = null)
    {
        $project_id = (int) $project_id;
        if ($project_id <= 0) {
            throw new Exception('Invalid param project_id');
        }
        if ($params && is_string($params)) {
            $status = array('active','completed');
            $filter = array('upcoming','late','today','tomorrow');
            if (in_array($params, $status)) {
                $params = array('status'=> $params);
            } elseif (in_array($params, $filter)) {
                $params = array('filter'=> $params);
            }
        }
        return $this->rest->get("projects/$project_id/$this->_action", $params);
    }

    /**
     * Reorder lists
     *
     * POST /projects/#{project_id}/todo_lists/reorders
     * Reorders the lists in the project according to the ordering given.
     * Any lists that are not explicitly specified will be positioned after the lists that are specified.
     *
     * @param int $project_id
     * @param array $ids
     * @return bool
     */
    public function reorder($project_id, array $ids)
    {
        $project_id = (int) $project_id;
        return $this->rest->post("projects/$project_id/$this->_action/reorder", $ids);
    }

    /**
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        $project_id = empty($data['project_id']) ? 0 : (int) $data['project_id'];
        if ($project_id <= 0) {
            throw new Exception('Required field project_id');
        }
        return $this->rest->post("projects/$project_id/$this->_action", $data);
    }
}