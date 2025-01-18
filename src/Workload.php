<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Resource;
use TeamWorkPm\Rest\Resource\GetAllTrait;

class Workload extends Resource
{
    use GetAllTrait;

    protected ?string $parent = 'workload';

    protected ?string $action = 'workload';

    protected string|array $fields = [];
}
