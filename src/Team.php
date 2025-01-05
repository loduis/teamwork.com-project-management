<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Resource\Model;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/teams/get-teams-json
 */
class Team extends Model
{
    protected ?string $parent = 'team';

    protected ?string $action = 'teams';

    protected string|array $fields = 'teams';
}