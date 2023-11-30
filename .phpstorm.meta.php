<?php
/** @see https://www.jetbrains.com/help/phpstorm/ide-advanced-metadata.html */

namespace PHPSTORM_META {

    override(\TeamWorkPm\Factory::build(), map([
        'category/file' => \TeamWorkPm\Category\File::class,
        'category/link' => \TeamWorkPm\Category\Link::class,
        'category/message' => \TeamWorkPm\Category\Message::class,
        'category/notebook' => \TeamWorkPm\Category\Notebook::class,
        'category/project' => \TeamWorkPm\Category\Project::class,

        'comment/file' => \TeamWorkPm\Comment\File::class,
        'comment/milestone' => \TeamWorkPm\Comment\Milestone::class,
        'comment/notebook' => \TeamWorkPm\Comment\Notebook::class,
        'comment/task' => \TeamWorkPm\Comment\Task::class,

        'portfolio/board' => \TeamWorkPm\Portfolio\Board::class,
        'portfolio/card' => \TeamWorkPm\Portfolio\Card::class,
        'portfolio/column' => \TeamWorkPm\Portfolio\Column::class,

        'account' => \TeamWorkPm\Account::class,
        'activity' => \TeamWorkPm\Activity::class,
        'company' => \TeamWorkPm\Company::class,
        'file' => \TeamWorkPm\File::class,
        'link' => \TeamWorkPm\Link::class,
        'message' => \TeamWorkPm\Message::class,
        'milestone' => \TeamWorkPm\Milestone::class,
        'notebook' => \TeamWorkPm\Notebook::class,
        'people' => \TeamWorkPm\People::class,
        'project' => \TeamWorkPm\Project::class,
        'role' => \TeamWorkPm\Role::class,
        'tag' => \TeamWorkPm\Tag::class,
        'task' => \TeamWorkPm\Task::class,
        'task/list' => \TeamWorkPm\Task_List::class,
        'time' => \TeamWorkPm\Time::class,
    ]));
}
