<?php
namespace TeamWorkPm;

class Permission extends Rest\Model
{
    protected function _init()
    {
        $this->_fields = array(
            'view_messages_and_files'=>array(
                'require'=>false,
                'attributes'=>array(
                    'type'=>'integer',
                    'validate'=>array(1, 0)
                )
            ),
            'view_tasks_and_milestones'=>array(
                'require'=>false,
                'attributes'=>array(
                    'type'=>'integer',
                    'validate'=>array(1, 0)
                )
            ),
            'view_time'=>array(
                'require'=>false,
                'attributes'=>array(
                    'type'=>'integer',
                    'validate'=>array(1, 0)
                )
            ),
            'view_notebooks'=>array(
                'require'=>false,
                'attributes'=>array(
                    'type'=>'integer',
                    'validate'=>array(1, 0)
                )
            ),
            'view_risk_register'=>array(
                'require'=>false,
                'attributes'=>array(
                    'type'=>'integer',
                    'validate'=>array(1, 0)
                )
            ),
            'view_invoices'=>array(
                'require'=>false,
                'attributes'=>array(
                    'type'=>'integer',
                    'validate'=>array(1, 0)
                )
            ),
            'view_links'=>array(
                'require'=>false,
                'attributes'=>array(
                    'type'=>'integer',
                    'validate'=>array(1, 0)
                )
            ),
            'add_tasks'=>array(
                'require'=>false,
                'attributes'=>array(
                    'type'=>'integer',
                    'validate'=>array(1, 0)
                )
            ),
            'add_milestones'=>array(
                'require'=>false,
                'attributes'=>array(
                    'type'=>'integer',
                    'validate'=>array(1, 0)
                )
            ),
            'add_taskLists'=>array(
                'require'=>false,
                'attributes'=>array(
                    'type'=>'integer',
                    'validate'=>array(1, 0)
                )
            ),
            'add_messages'=>array(
                'require'=>false,
                'attributes'=>array(
                    'type'=>'integer',
                    'validate'=>array(1, 0)
                )
            ),
            'add_files'=>array(
                'require'=>false,
                'attributes'=>array(
                    'type'=>'integer',
                    'validate'=>array(1, 0)
                )
            ),
            'add_time'=>array(
                'require'=>false,
                'attributes'=>array(
                    'type'=>'integer',
                    'validate'=>array(1, 0)
                )
            ),
            'add_links'=>array(
                'require'=>false,
                'attributes'=>array(
                    'type'=>'integer',
                    'validate'=>array(1, 0)
                )
            ),
            'set_privacy'=>array(
                'require'=>false,
                'attributes'=>array(
                    'type'=>'integer',
                    'validate'=>array(1, 0)
                )
            ),
            'can_be_assigned_to_tasks_and_milestones'=>array(
                'require'=>false,
                'attributes'=>array(
                    'type'=>'integer',
                    'validate'=>array(1, 0)
                )
            ),
            'project_administrator'=>array(
                'require'=>false,
                'attributes'=>array(
                    'type'=>'integer',
                    'validate'=>array(1, 0)
                )
            ),
            'add_people_to_project'=>array(
                'require'=>false,
                'attributes'=>array(
                    'type'=>'integer',
                    'validate'=>array(1, 0)
                )
            )
        );
        $this->_parent = 'permissions';
    }

    /**
     * Update a users permissions on a project
     * PUT /projects/{id}/people/{id}.xml
     * Sets the permissions of a given user on a given project.
     */
    public function update(array $data)
    {
        $project_id = (int) (empty($data['project_id']) ? 0: $data['project_id']);
        $id = (int) (empty($data['id']) ? 0: $data['id']);
        if ($project_id <= 0) {
            throw new Exception("Require field project_id");
        }
        if ($id <= 0) {
            throw new Exception("Require field id");
        }
        return $this->rest->put("projects/$project_id/people/$id", $data);
    }
}