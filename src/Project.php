<?php

declare(strict_types=1);

namespace TeamWorkPm;

final class Project extends Resource
{
    /**
     * All of the companies within the specified project are returned
     *
     * @return Company[]
     */
    public function companies()
    {
        return static::instanceGetRequest($this->id . '/companies', [
            'instance_of' => Company::class,
        ]);
    }
}
