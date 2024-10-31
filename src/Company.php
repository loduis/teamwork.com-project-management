<?php

namespace TeamWorkPm;

use TeamWorkPm\Response\Model as Response;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/companies/get-companies-json
 */
class Company extends Model
{
    protected static array|string $fields = 'companies';

    /**
     * Retrieve Companies
     *
     * @param array|object $params
     *
     * @return Response
     * @throws Exception
     */
    public function all(array|object $params = []): Response
    {
        return $this->rest->get((string) $this->action, $params);
    }

    /**
     * Retrieving Companies within a Project
     *
     * @param int $id
     *
     * @return Response
     * @throws Exception
     */
    public function getByProject(int $id): Response
    {
        return $this->rest->get("projects/$id/$this->action");
    }
}
