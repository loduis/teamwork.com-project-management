<?php

namespace TeamWorkPm\Comment;

use TeamWorkPm\Comment;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/comments
 */
class Task extends Comment
{
    protected ?string $resource = 'tasks';
}
