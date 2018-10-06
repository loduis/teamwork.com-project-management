<?php

namespace TeamWorkPm\Tests;

use TeamWorkPm\Api;
use GuzzleHttp\HandlerStack;
use Illuminate\Api\Testing\ApiHandler;
use Illuminate\Api\Testing\TestCase as BaseTestCase;

/**
 * Base class for Alegra test cases, provides some utility methods for creating
 * objects.
 */
abstract class TestCase extends BaseTestCase
{
    protected function setUp()
    {
        static $handle;

        $apiUser = getenv('API_USER');
        $apiKey = getenv('API_KEY');
        $mode = getenv('API_ENV');
        $this->createHandler($mode);
        Api::auth($apiUser, $apiKey);
    }

    protected function createHandler($mode)
    {
        static $handler;

        if ($mode !== 'live') {
            if (!$handler) {
                $handler = ApiHandler::create(__DIR__ . '/schemas');
            }
            $stack = HandlerStack::create($handler);
            Api::clientOptions([
                'handler' => $stack
            ]);
        }
    }
}
