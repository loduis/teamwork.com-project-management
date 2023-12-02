<?php

namespace TeamWorkPm\Support;

use GuzzleHttp\Psr7\Utils;
use Illuminate\Support\Arr;

trait Getting
{
    /**
     * This is not and normal rest api need fixed response
     *
     * @param  [type] $response
     * @return [type]
     */
    final public static function onGettingResource($response)
    {
        $data = (array) json_decode($response->getBody(), true);

        if (Arr::has($data, 'STATUS')) {
            Arr::forget($data, 'STATUS');
            $data = current($data); // get the actual object
        }
        $data = json_encode($data);

        return $response->withBody(Utils::streamFor($data));
    }
}
