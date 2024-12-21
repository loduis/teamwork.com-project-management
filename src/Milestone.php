<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\CompleteTrait;
use TeamWorkPm\Rest\ProjectTrait;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/milestones/get-milestones-json
 */
class Milestone extends Model
{
    use ProjectTrait, CompleteTrait;

    protected ?string $parent = 'milestone';

    protected ?string $action = 'milestones';

    protected string|array $fields = 'milestones';
}
