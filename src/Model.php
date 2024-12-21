<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\ResourceTrait;

abstract class Model extends Rest\Resource
{
    use ResourceTrait;
}
