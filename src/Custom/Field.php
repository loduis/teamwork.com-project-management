<?php

namespace TeamWorkPm\Custom;

use TeamWorkPm\Exception;
use TeamWorkPm\Model;
use TeamWorkPm\Response\Model as Response;

class Field extends Model
{
    protected ?string $action = 'projects/api/v3/customfields';

    protected ?string $parent = 'customfield';

    protected static string|array $fields = 'projects.custom_fields';

    /**
     * Get all custom fields
     *
     * @param array $params
     * @return Response
     * @throws Exception
     */
    public function all(object|array $params = []): Response
    {
        return $this->rest->get("$this->action", $params);
    }
}
