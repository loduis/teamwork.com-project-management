<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Resource;
use TeamWorkPm\Rest\Resource\GetAllTrait;

class Timezone extends Resource
{
    use GetAllTrait;

    protected ?string $parent = 'timezones';

    protected ?string $action = 'timezones';

    protected string|array $fields = [];
}
