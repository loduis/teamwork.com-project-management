<?php

declare(strict_types = 1);

namespace TeamWorkPm\Comment;

use TeamWorkPm\Comment;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/comments
 */
class Link extends Comment
{
    protected ?string $resource = 'links';
}