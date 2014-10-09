<?php namespace TeamWorkPm;

class Task_List extends Model
{
    protected function init()
    {
        $this->fields = [
            'name'   => true,
            'private' => [
                'required'=>false,
                'attributes'=>[
                    'type'=>'boolean'
                ]
            ],
            'pinned'   =>[
                'required'=>false,
                'attributes'=>[
                    'type'=>'boolean'
                ]
            ],
            'tracked' => [
                'required'=>false,
                'attributes'=>[
                    'type'=>'boolean'
                ]
            ],
            'description'           => false,
            'milestone_id'          => [
                'required'=>false,
                'attributes'=>[
                    'type'=>'integer'
                ]
            ],
            'todo_list_template_id' => false
        ];
        $this->parent = 'todo-list';
        $this->action = 'todo_lists';
    }

    /**
     * Retrieve Single todo list
     * GET /todo_lists/#{id}.xml
     * GET /todo_lists/#{id}.xml?showTasks=no
     *
     * Retrieves the todo list corresponding to the submitted integer ID.
     * if you pass showTasks=no, no tasks will be returned (showTasks defaults to "yes").
     */
    public function get($id, $show_tasks = true)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        $params = [];
        if (!$show_tasks) {
            $params['showTasks'] = 'no';
        }
        return $this->rest->get("$this->action/$id", $params);
    }

    /**
     * Get all task lists for a project
     *
     * GET /projects/#{project_id}/todo_lists.xml
     * GET /projects/#{project_id}/todo_lists.xml?showTasks=no
     * GET /projects/#{project_id}/todo_lists.xml?responsible-party-id=#{id}
     * GET /projects/#{project_id}/todo_lists.xml?getOverdueCount=yes
     * GET /projects/#{project_id}/todo_lists.xml?responsible-party-id=#{id}&getOverdueCount=yes
     * GET /projects/#{project_id}/todo_lists.xml?status=completed&getCompletedCount=yes
     * Retrieves all project task lists
     * Options:
     * You can pass 'showMilestones=yes' if you would like to get information on Milestones associated with each task list
     * You can pass 'showTasks=no' if you do not want to have the tasks returned (showTasks defaults to "yes").
     *
     * If 'responsible-party-id' is passed lists returned will be filtered to those with tasks for the user.
     * Passing "getOverdueCount" will return the number of overdue tasks ("overdue-count") for each task list.
     * Passing "getCompletedCount" will return the number of completed tasks ("completed-count") for each task list.
     * Status: You can use the Status option to restrict the lists return - valid values are 'all', 'active', and 'completed'. The default is "ACTIVE"
     * Filter: You can use the Filter option to return specific tasks - valid values are 'all','upcoming','late','today','tomorrow'. The default is "ALL"
     * If you pass FILTER as upcoming, late, today or tomorrow, you can also pass includeOverdue to also include overdue tasks
     *
     * @param [int] $id
     * @param [string | array] $params
     * @return object
     */
    public function getByProject($id, $params = null)
    {
        $project_id = (int) $id;
        if ($project_id <= 0) {
            throw new Exception('Invalid param project_id');
        }
        if ($params && is_string($params)) {
            $status = ['active','completed'];
            $filter = ['upcoming','late','today','tomorrow'];
            if (in_array($params, $status)) {
                $params = ['status'=> $params];
            } elseif (in_array($params, $filter)) {
                $params = ['filter'=> $params];
            } else {
                $params = null;
            }
        }
        return $this->rest->get("projects/$project_id/$this->action", $params);
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
        return $this->rest->post("projects/$project_id/$this->action/reorder", $ids);
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
        return $this->rest->post("projects/$project_id/$this->action", $data);
    }
}