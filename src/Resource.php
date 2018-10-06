<?php

namespace TeamWorkPm;

use Illuminate\Api\Http\Restable;
use Illuminate\Api\Http\Resource as ApiResource;

/**
 * Base resource
 */
abstract class Resource extends ApiResource
{
    public static $snakeAttributes = true;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = [
        'id' => 'string'
    ];

    use Restable;
    use Support\Getting;
}
