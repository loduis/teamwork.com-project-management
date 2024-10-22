<?php

declare(strict_types=1);

namespace TeamWorkPm;

/**
 * Class Project
 *
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/projects/get-projects-json
 *
 * This class represents a project in the TeamWorkPm system. It contains the fields and methods
 * necessary to interact with the project API, including creating, retrieving, and managing projects
 * and their states (active, archived, starred, etc.).
 *
 * @package TeamWorkPm
 */
class Project extends Model
{
    /**
     * Initialize project fields.
     *
     * Fields define the structure and validation rules for the project data, such as required
     * fields, data types, and transformations. This includes fields like `name`, `description`,
     * and options for project features like tasks, milestones, and comments.
     */
    protected function init()
    {
        $this->fields = [
            'name' => [
                'type' => 'string',
                'required' => true
            ],
            'description' => [
                'type' => 'string'
            ],
            'use_tasks' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'use_milestones' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'use_messages' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'use_files' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'use_time' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'use_notebook' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'use_risk_register' => [
                'type' => 'boolean',
                'transform' => 'use-riskregister'
            ],
            'use_links' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'use_billing' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'use_comments' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'category_id' => [
                'type' => 'integer'
            ],
            'start_date' => [
                'type' => 'string',
                'transform' => 'dash'
            ],
            'end_date' => [
                'type' => 'string',
                'transform' => 'dash'
            ],
            'tag_ids' => [
                'type' => 'string',
                'transform' => 'camel'
            ],
            'onboarding' => [
                'type' => 'boolean'
            ],
            'grant_access_to' => [
                'type' => 'string',
                'transform' => 'dash'
            ],
            'private' => [
                'type' => 'boolean'
            ],
            'custom_fields' => [
                'type' => 'array',
                'transform' => ['camel', function ($value) {
                    return array_is_list($value) ? $value : array_reduce($value, function ($acc, $value, $key) {
                        $acc[] = [
                            'customFieldId' => (int) $key,
                            'value' => (string) $value
                        ];
                        return $acc;
                    }, []);
                }]
            ],
            'people' => [
                'type' => 'integer'
            ],
            'project_owner_id' => [
                'type' => 'integer',
                'transform' => 'camel'
            ],
            'company_id' => [
                'type' => 'integer',
                'transform' => 'camel'
            ],
            'template_date_target_default' => [
                'type' => 'string',
                'transform' => 'camel',
                'on_update' => true
            ],
            'status' => [
                'type' => 'string',
                'on_update' => true
            ],
            'logo_pending_file_ref' => [
                'type' => 'string',
                'transform' => 'camel',
                'on_update' => true,
            ]
        ];
    }

    /**
     * Retrieves all accessible projects, including active, inactive, and archived projects.
     * You can optionally pass a date to get only recently updated projects, useful for caching purposes.
     *
     * @param array $params Optional query parameters
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function all(array $params = [])
    {
        return $this->getByStatus('all', $params);
    }

    /**
     * Retrieve all active projects.
     *
     * @param array $params Optional query parameters
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function getActive(array $params = [])
    {
        return $this->getByStatus('active', $params);
    }

    /**
     * Retrieve all archived projects.
     *
     * @param array $params Optional query parameters
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function getArchived(array $params = [])
    {
        return $this->getByStatus('archived', $params);
    }

    /**
     * Retrieves all starred projects.
     *
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function getStarred()
    {
        return $this->rest->get("$this->action/starred");
    }

    /**
     * Marks a project as starred.
     *
     * @param int $id Project ID
     * @return bool
     * @throws \TeamWorkPm\Exception
     */
    public function star(int $id)
    {
        $this->validateId($id);

        return $this->rest->put("$this->action/$id/star");
    }

    /**
     * Un mark a project as starred.
     *
     * @param int $id Project ID
     * @return bool
     * @throws \TeamWorkPm\Exception
     */
    public function unStar(int $id)
    {
        $this->validateId($id);

        return $this->rest->put("$this->action/$id/unstar");
    }

    /**
     * Activates a project (sets it as active).
     *
     * @param int $id Project ID
     * @return bool
     * @throws \TeamWorkPm\Exception
     */
    public function activate(int $id)
    {
        $this->validateId($id);

        return $this->update(['id' => $id, 'status' => 'active']);
    }

    /**
     * Archives a project.
     *
     * @param int $id Project ID
     * @return bool
     * @throws \TeamWorkPm\Exception
     */
    public function archive(int $id)
    {
        $this->validateId($id);

        return $this->update(['id' => $id, 'status' => 'archived']);
    }

    /**
     * Retrieves projects by their status (active, archived, all).
     *
     * @param string $status Project status
     * @param array $params Optional query parameters
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    private function getByStatus(string $status, array $params = [])
    {
        $params['status'] = strtoupper($status);

        return $this->rest->get("$this->action", $params);
    }
}
