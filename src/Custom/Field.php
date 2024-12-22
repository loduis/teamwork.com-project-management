<?php

declare(strict_types = 1);

namespace TeamWorkPm\Custom;

use TeamWorkPm\Rest\Resource\Model;

class Field extends Model
{
    protected ?string $action = 'projects/api/v3/customfields';

    protected ?string $parent = 'customfield';

    protected string|array $fields = 'projects.custom_fields';
}
