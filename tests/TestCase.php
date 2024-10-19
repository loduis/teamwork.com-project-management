<?php

namespace TeamWorkPm\Tests;

use Closure;
use TeamWorkPm\Factory;
use TeamWorkPm\Request\JSON as Request;
use TeamWorkPm\Response\JSON as Response;
use TeamWorkPm\Rest\Client as HttpClient;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function tpm(string $className, string $method = 'get', ?Closure $callback = null)
    {
        [$className, $key, $url] = Factory::resolve($className);
        $http = $this->getMockBuilder(HttpClient::class)
            ->setConstructorArgs([$url, $key])
            ->getMock();
        $http->expects($this->once())
            ->method('getRequest')
            ->willReturnCallback(function () {
                return new Request();
            });
        if ($callback === null) {
            $callback = function (string $path) use ($method) {
                $body = file_get_contents(__DIR__ . '/fixtures/' . $path . '.json');
                $headers = [
                    'Status' => 200,
                    'Method' => strtoupper($method),
                    'X-Action' => $path
                ];
                return (new Response())->parse($body, $headers);
            };
        }
        $http->method($method)
        ->willReturnCallback($callback);

        return new $className($http);
    }
}
