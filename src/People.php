<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Resource\Company\GetByTrait;
use TeamWorkPm\Rest\Resource\Model;
use TeamWorkPm\Rest\Response\Model as Response;
use TeamWorkPm\Rest\Resource\GetAllTrait;
use TeamWorkPm\Rest\Resource\TagTrait;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/people/get-people-json
 * @todo Add/Remove People to existing Project
 */
class People extends Model
{
    use GetAllTrait, GetByTrait, TagTrait;

    protected ?string $parent = 'person';

    protected ?string $action = 'people';

    protected string|array $fields = 'people';

    /**
     * Retrieve all API Keys for all People on account
     *
     * @param array|object $params
     *
     * @return Response
     * @throws Exception
     */

    public function getApiKeys(): Response
    {
        return $this->fetch("$this->action/APIKeys");
    }

    /**
     * Get available People for a Calendar Event
     * Get available People for a Message
     * Get available People for a Milestone
     * Get available People for following a Notebook
     * Get available People for a Task
     * Get available People to notify when adding a File
     * Get available People to notify when adding a Link
     *
     * @param string $resource
     * @param object|array $params
     *
     * @return Response
     * @throws Exception
     */
    public function getAvailableFor(string $resource, object|array $params = []): Response
    {
        if (!in_array($resource, [
                'calendar_events',
                'messages',
                'milestones',
                'notebooks',
                'tasks',
                'files',
                'links']
        )) {
            throw new Exception("Invalid resource for available: " . $resource);
        }

        $params = arr_obj($params);

        [$path, $subpath, $id, $isCalendar] = match($resource) {
            'calendar_events' => [
                'calendarevents',
                null,
                (int) $params->pull('event_id'),
                true
            ],
            default => [
                'projects',
                $resource,
                (int) $params->pull('project_id'),
                false
            ]
        };

        if ($isCalendar) {
            $this->validates(['event_id' => $id]);
        } else {
            $this->validates(['project_id' => $id]);
        }

        $path .= "/$id/";
        if (!$isCalendar) {
            $path .= "$subpath/";
        }
        $path .= 'availablepeople';

        return $this->fetch($path, $params);
    }


    /**
     * Get all deleted People
     *
     * @return Response
     * @throws Exception
     */
    public function getDeleted(object|array $params = []): Response
    {
        return $this->fetch("$this->action/deleted", $params);
    }

    /**
     * Get all People (within a Project)
     * And
     * Get a Users Permissions on a Project
     *
     * @param int $id
     * @param ?int $personId
     *
     * @return Response
     * @throws Exception
     */
    public function getByProject(int $id, ?int $personId = null): Response
    {
        $path = "projects/$id/$this->action";
        if ($personId !== null) {
            $path .= '/' . $personId;
        }

        return $this->fetch($path);
    }

    /**
     * Creates a new User Account
     *
     * @param array|object $data
     * @return integer
     */
    public function create(array|object $data): int
    {
        $data = arr_obj($data);
        $projectId = (int) $data->pull('project_id');
        $permissions = $data->pull('permissions');
        $id = parent::create($data);
        if ($projectId) {
            $permission = Factory::projectPeople();
            if ($permission->add($projectId, $id) && $permissions !== null) {
                $permissions['person_id'] = $id;
                $permissions['project_id'] = $projectId;
                $permission->update($permissions);
            }
        }

        return $id;
    }

    /**
     * Editing a User
     *
     * @param array|object $data
     * @return boolean
     */
    public function update(array|object $data): bool
    {
        $data = arr_obj($data);
        $projectId = (int) $data->pull('project_id');
        $permissions = $data->pull('permissions');
        $id = (int) $data->pull('id');
        $save = true;
        if ($data->has()) {
            $data['id'] = $id;
            $save = parent::update($data);
        }
        // add permission to project
        if ($projectId) {
            $permission = Factory::projectPeople();
            try {
                $add = $permission->add($projectId, $id);
            } catch (Exception $e) {
                $add = $e->getMessage() == 'User is already on project';
            }
            $save = $save && $add;
            if ($add && $permissions !== null && $permissions) {
                $permissions['person_id'] = $id;
                $permissions['project_id'] = $projectId;
                $save = $permission->update($permissions);
            }
        }

        return $save;
    }

    /**
     * Get Logged Time by Person
     *
     * @param int $id
     * @param array|object $params
     * @return Response
     * @throws Exception
     */
    public function getLoggedTime(int $id, array|object $params = []): Response
    {
        return $this->fetch("$this->action/$id/loggedtime", $params);
    }

    /**
     * Get all Running Timers for a specific Person
     *
     * @param int $id
     * @param array|object $params
     * @return Response
     * @throws Exception
     */
    public function getTimers(int $id, array|object $params = []): Response
    {
        return $this->fetch("$this->action/$id/timers", $params);
    }
}
