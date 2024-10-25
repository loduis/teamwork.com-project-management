<?php

declare(strict_types = 1);

namespace TeamWorkPm;

/**
 * Class Project
 *
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/projects/get-projects-json
 *
 * This class represents a project in the TeamWorkPm system. It contains the fields and methods
 * necessary to interact with the project API, including creating, retrieving, and managing projects
 * and their states (active, archived, starred, etc.).
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
                'transform' => ['camel', function (array $value): array {
                    /**
                     * @var array
                     */
                    return array_reduce($value, function (array $acc, string $value, int $key) {
                        $acc[] = [
                            'customFieldId' => $key,
                            'value' => $value
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
     * @param object|array $params Optional query parameters
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function all(object|array $params = [])
    {
        return $this->getByStatus('all', $params);
    }

    /**
     * Retrieve all active projects.
     *
     * @param array $params Optional query parameters
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
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
     * @throws Exception
     */
    public function getArchived(array $params = [])
    {
        return $this->getByStatus('archived', $params);
    }

    /**
     * Retrieves all starred projects.
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function getStarred()
    {
        return $this->rest->get("$this->action/starred");
    }

    /**
     * Get Project Rates
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function getRates(int $id, array $params = [])
    {
        return Factory::build('project.rate')->get($id, $params);
    }

    /**
     * set Project Rates
     *
     * @return bool
     * @throws \TeamWorkPm\Exception
     */
    public function setRates(int $id, array $data = [])
    {
        return Factory::projectRate()->set($id, $data);
    }

    /**
     * Get Project Stats
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function getStats(int $id, object|array $params = [])
    {
        return $this->rest->get("$this->action/$id/stats", $params);
    }

    /**
     * Retrieve Projects assigned to a specific Company
     *
     * @param int $id
     * @param array $params
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function getByCompany(int $id, array $params = [])
    {
        return $this->rest->get("companies/$id/$this->action", $params);
    }

    /**
     * Marks a project as starred.
     *
     * @param int $id Project ID
     * @return bool
     * @throws Exception
     */
    public function star(int $id)
    {
        $this->validateId($id);

        return $this->rest->put("$this->action/$id/star") !== false;
    }

    /**
     * Un mark a project as starred.
     *
     * @param int $id Project ID
     * @return bool
     * @throws Exception
     */
    public function unStar(int $id)
    {
        $this->validateId($id);

        return $this->rest->put("$this->action/$id/unstar") !== false;
    }

    /**
     * Activates a project (sets it as active).
     *
     * @param int $id Project ID
     * @return bool
     * @throws Exception
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
     * @throws Exception
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
     * @param object|array $params Optional query parameters
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    private function getByStatus(string $status, object|array $params = [])
    {
        $params = arr_obj($params);
        $params['status'] = strtoupper($status);

        return $this->rest->get("$this->action", $params);
    }
}
