<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Resource\Model;
use TeamWorkPm\Rest\Resource\ProjectTrait;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/links/get-projects-id-links-json
 * @see TeamWorkPm\Resource\schemas\links.json
 */
class Link extends Model
{
    use ProjectTrait;

    protected ?string $parent = 'link';

    protected ?string $action = 'links';

    protected string|array $fields = 'links';
}
