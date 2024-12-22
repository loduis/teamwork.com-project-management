<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Resource\Model;
use TeamWorkPm\Rest\Response\Model as Response;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/projects/get-projects-json
 */
class Project extends Model
{
    protected ?string $parent = 'project';

    protected ?string $action = 'projects';

    protected string|array $fields = 'projects';

    /**
     * Retrieves all accessible projects
     *
     * @param object|array $params Optional query parameters
     * @return Response
     * @throws Exception
     */
    public function all(object|array $params = []): Response
    {
        return $this->getByStatus('all', $params);
    }

    /**
     * Retrieve all active projects.
     *
     * @param array $params Optional query parameters
     * @return Response
     * @throws Exception
     */
    public function getActive(array $params = []): Response
    {
        return $this->getByStatus('active', $params);
    }

    /**
     * Retrieve all archived projects.
     *
     * @param array $params Optional query parameters
     * @return Response
     * @throws Exception
     */
    public function getArchived(array $params = []): Response
    {
        return $this->getByStatus('archived', $params);
    }

    /**
     * Retrieves all starred projects.
     *
     * @return Response
     * @throws Exception
     */
    public function getStarred(): Response
    {
        return $this->fetch("$this->action/starred");
    }

    /**
     * Get Project Rates
     *
     * @param int $id
     * @param object|array $params
     *
     * @return Response
     * @throws Exception
     */
    public function getRates(int $id, object|array $params = []): Response
    {
        return Factory::projectRate()->get($id, $params);
    }

    /**
     * Set Project Rates
     *
     * @param int $id
     * @param object|array $data

     * @return bool
     * @throws Exception
     */
    public function setRates(int $id, object|array $data = []): bool
    {
        return Factory::projectRate()->set($id, $data);
    }

    /**
     * Get all People (within a Project)
     *
     * @param int $id
     *
     * @return Response
     * @throws Exception
     */
    public function getPeople(int $id): Response
    {
        return Factory::people()->getByProject($id);
    }

    /**
     * Get all People (within a Project)
     *
     * @param int $id
     *
     * @return Response
     * @throws Exception
     */
    public function getFiles(int $id): Response
    {
        return Factory::file()->getByProject($id);
    }

    /**
     * Get Project Stats
     *
     * @return Response
     * @throws Exception
     */
    public function getStats(int $id, object|array $params = []): Response
    {
        return $this->fetch("$this->action/$id/stats", $params);
    }

    /**
     * Retrieve Projects assigned to a specific Company
     *
     * @param int $id
     * @param object|array $params
     *
     * @return Response
     * @throws Exception
     */
    public function getByCompany(int $id, object|array $params = []): Response
    {
        return $this->fetch("companies/$id/$this->action", $params);
    }

    /**
     * Marks a project as starred.
     *
     * @param int $id Project ID
     * @return bool
     * @throws Exception
     */
    public function star(int $id): bool
    {
        $this->validates(['id' => $id]);
        /** @var bool */
        return $this->put("$this->action/$id/star");
    }

    /**
     * Un mark a project as starred.
     *
     * @param int $id Project ID
     * @return bool
     * @throws Exception
     */
    public function unStar(int $id): bool
    {
        $this->validates(['id' => $id]);
        /** @var bool */
        return $this->put("$this->action/$id/unstar");
    }

    /**
     * Activates a project (sets it as active).
     *
     * @param int $id Project ID
     * @return bool
     * @throws Exception
     */
    public function activate(int $id): bool
    {
        $this->validates(['id' => $id]);

        return $this->update(['id' => $id, 'status' => 'active']);
    }

    /**
     * Archives a project.
     *
     * @param int $id Project ID
     * @return bool
     * @throws Exception
     */
    public function archive(int $id): bool
    {
        $this->validates(['id' => $id]);

        return $this->update(['id' => $id, 'status' => 'archived']);
    }

    /**
     * Retrieves projects by their status (active, archived, all).
     *
     * @param string $status
     * @param object|array $params
     * @return Response
     * @throws Exception
     */
    private function getByStatus(string $status, object|array $params = []): Response
    {
        $params = arr_obj($params);
        $params['status'] = strtoupper($status);

        return $this->fetch("$this->action", $params);
    }
}
