<?php

namespace TeamWorkPm\Custom;

use TeamWorkPm\Exception;
use TeamWorkPm\Model;
use TeamWorkPm\Response\Model as Response;

class Field extends Model
{
    protected ?string $action = 'projects/api/v3/customfields';

    protected ?string $parent = 'customfield';

    protected string|array $fields = 'projects.custom_fields';
}
