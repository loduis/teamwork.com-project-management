<?php

declare(strict_types = 1);

namespace TeamWorkPm\Category;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/message-categories
 */
class Message extends Model
{
    protected ?string $action = 'messageCategories';
}
