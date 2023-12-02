<?php

declare(strict_types=1);

namespace TeamWorkPm\Support;

use GuzzleHttp\Psr7\Utils;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\MessageInterface;

trait Getting
{
    /**
     * This is not and normal rest api need fixed response
     *
     * @param  ResponseInterface $response
     * @return MessageInterface
     */
    final public static function onGettingResource(ResponseInterface $response): MessageInterface
    {
        $data = (array) json_decode((string) $response->getBody(), true);

        if (Arr::has($data, 'STATUS')) {
            Arr::forget($data, 'STATUS');
            $data = current($data); // get the actual object
        }

        return $response->withBody(Utils::streamFor(json_encode($data)));
    }
}
