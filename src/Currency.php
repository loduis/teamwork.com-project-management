<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Resource;
use TeamWorkPm\Rest\Resource\GetAllTrait;

class Currency extends Resource
{
    use GetAllTrait;

    protected ?string $parent = 'currency-codes';

    protected ?string $action = 'currencycodes';

    protected string|array $fields = [];
}
