<?php

declare(strict_types = 1);

namespace TeamWorkPm\Rest\Resource;

trait ActionTrait
{
    use GetAllTrait, GetTrait, StoreTrait, DestroyTrait;
}