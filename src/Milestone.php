<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Resource\Model;
use TeamWorkPm\Rest\Resource\CompleteTrait;
use TeamWorkPm\Rest\Resource\Project\ActionTrait as ProjectTrait;
use TeamWorkPm\Rest\Resource\TagTrait;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/milestones/get-milestones-json
 */
class Milestone extends Model
{
    use ProjectTrait, CompleteTrait, TagTrait;

    protected ?string $parent = 'milestone';

    protected ?string $action = 'milestones';

    protected string|array $fields = 'milestones';
}
