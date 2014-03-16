<?php namespace TeamWorkPm\Project;

class People extends \TeamWorkPm\Rest\Model
{
    protected function init()
    {
        $this->fields = [
            'view_messages_and_files'=>[
                'require'=>false,
                'attributes'=>[
                    'type'=>'integer',
                    'validate'=>[1, 0]
                ]
            ],
            'view_tasks_and_milestones'=>[
                'require'=>false,
                'attributes'=>[
                    'type'=>'integer',
                    'validate'=>[1, 0]
                ]
            ],
            'view_time'=>[
                'require'=>false,
                'attributes'=>[
                    'type'=>'integer',
                    'validate'=>[1, 0]
                ]
            ],
            'view_notebooks'=>[
                'require'=>false,
                'attributes'=>[
                    'type'=>'integer',
                    'validate'=>[1, 0]
                ]
            ],
            'view_risk_register'=>[
                'require'=>false,
                'attributes'=>[
                    'type'=>'integer',
                    'validate'=>[1, 0]
                ]
            ],
            'view_invoices'=>[
                'require'=>false,
                'attributes'=>[
                    'type'=>'integer',
                    'validate'=>[1, 0]
                ]
            ],
            'view_links'=>[
                'require'=>false,
                'attributes'=>[
                    'type'=>'integer',
                    'validate'=>[1, 0]
                ]
            ],
            'add_tasks'=>[
                'require'=>false,
                'attributes'=>[
                    'type'=>'integer',
                    'validate'=>[1, 0]
                ]
            ],
            'add_milestones'=>[
                'require'=>false,
                'attributes'=>[
                    'type'=>'integer',
                    'validate'=>[1, 0]
                ]
            ],
            'add_taskLists'=>[
                'require'=>false,
                'attributes'=>[
                    'type'=>'integer',
                    'validate'=>[1, 0]
                ]
            ],
            'add_messages'=>[
                'require'=>false,
                'attributes'=>[
                    'type'=>'integer',
                    'validate'=>[1, 0]
                ]
            ],
            'add_files'=>[
                'require'=>false,
                'attributes'=>[
                    'type'=>'integer',
                    'validate'=>[1, 0]
                ]
            ],
            'add_time'=>[
                'require'=>false,
                'attributes'=>[
                    'type'=>'integer',
                    'validate'=>[1, 0]
                ]
            ],
            'add_links'=>[
                'require'=>false,
                'attributes'=>[
                    'type'=>'integer',
                    'validate'=>[1, 0]
                ]
            ],
            'set_privacy'=>[
                'require'=>false,
                'attributes'=>[
                    'type'=>'integer',
                    'validate'=>[1, 0]
                ]
            ],
            'can_be_assigned_to_tasks_and_milestones'=>[
                'require'=>false,
                'attributes'=>[
                    'type'=>'integer',
                    'validate'=>[1, 0]
                ]
            ],
            'project_administrator'=>[
                'require'=>false,
                'attributes'=>[
                    'type'=>'integer',
                    'validate'=>[1, 0]
                ]
            ],
            'add_people_to_project'=>[
                'require'=>false,
                'attributes'=>[
                    'type'=>'integer',
                    'validate'=>[1, 0]
                ]
            ]
        ];
        $this->action = $this->parent = 'permissions';
    }

    public function get($project_id, $person_id)
    {
        $this->validates($project_id, $person_id);
        return $this->rest->get("/projects/$project_id/people/$person_id");
    }

    public function add($project_id, $person_id)
    {
        $this->validates($project_id, $person_id);
        return $this->rest->post("projects/$project_id/people/$person_id");
    }

    /**
     * Update a users permissions on a project
     * PUT /projects/{id}/people/{id}.xml
     * Sets the permissions of a given user on a given project.
     */
    public function update(array $data)
    {
        $project_id = (int) (empty($data['project_id']) ? 0: $data['project_id']);
        $person_id = (int) (empty($data['person_id']) ? 0: $data['person_id']);
        if ($project_id <= 0) {
            throw new \TeamWorkPm\Exception("Required field project_id");
        }
        if ($person_id <= 0) {
            throw new \TeamWorkPm\Exception("Required field person_id");
        }
        return $this->rest->put("projects/$project_id/people/$person_id", $data);
    }

    public function delete($project_id, $person_id)
    {
        $this->validates($project_id, $person_id);
        return $this->rest->delete("/projects/$project_id/people/$person_id");
    }

    private function validates($project_id, $person_id)
    {
        $project_id = (int) $project_id;
        if ($project_id <= 0) {
            throw new \TeamWorkPm\Exception('Invalid param project_id');
        }
        $person_id = (int) $person_id;
        if ($person_id <= 0) {
            throw new \TeamWorkPm\Exception('Invalid param person_id');
        }
    }
}