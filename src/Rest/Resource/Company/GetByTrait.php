<?php

namespace TeamWorkPm\Rest\Resource\Company;

use TeamWorkPm\Rest\Response\Model as Response;

trait GetByTrait
{
     /**
     * Get resource (within a Company)
     *
     * @param int $id
     * @param array|object $params
     * @return Response
     * @throws Exception
     */
    public function getByCompany(int $id, array|object $params = []): Response
    {
        return $this->fetch("companies/$id/$this->action", $params);
    }
}