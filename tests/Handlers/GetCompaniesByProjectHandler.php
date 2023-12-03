<?php

namespace TeamWorkPm\Tests\Handlers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Api\Testing\Handlers\ResponseHandler;

class GetCompaniesByProjectHandler
{
    public static function requestHandle(RequestInterface $request, array &$options)
    {
        return 200;
    }

    public static function responseHandle(ResponseInterface $response)
    {
        $handler = new ResponseHandler($response);

        $companies = file_get_contents(__DIR__ . '/../schemas/get.companies.json');

        $companies = json_decode($companies);

        $handler->setBody($companies);

        return $handler->getResponse();
    }
}