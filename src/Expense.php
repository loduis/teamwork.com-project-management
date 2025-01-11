<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Resource\Model;
use TeamWorkPm\Rest\Resource\Project\GetByTrait;

class Expense extends Model
{
    use GetByTrait;

    protected ?string $parent = 'expense';

    protected ?string $action = 'expenses';

    protected string|array $fields = 'expenses';
}
