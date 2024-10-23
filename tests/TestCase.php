<?php

namespace TeamWorkPm\Tests;

use Closure;
use stdClass;
use TeamWorkPm\Factory;
use TeamWorkPm\Request\JSON as Request;
use TeamWorkPm\Response\JSON as Response;
use TeamWorkPm\Rest\Client as HttpClient;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function getTpm(string $className, ?Closure $callback = null)
    {
        return $this->tpm($className, 'get', $callback);
    }

    protected function postTpm(string $className, ?Closure $callback = null)
    {
        return $this->tpm($className, 'post', $callback);
    }

    protected function putTpm(string $className, ?Closure $callback = null)
    {
        return $this->tpm($className, 'put', $callback);
    }

    protected function delTpm(string $className, ?Closure $callback = null)
    {
        return $this->tpm($className, 'delete', $callback);
    }

    private function tpm(string $className, string $method = 'get', ?Closure $callback = null)
    {
        $method = strtoupper($method);
        [$className, $key, $url] = Factory::resolve($className);
        $http = $this->getMockBuilder(HttpClient::class)
            ->setConstructorArgs([$url, $key])
            ->getMock();
        $mockData = new stdClass;
        $http->expects($this->once())
            ->method('configRequest')
            ->willReturnCallback(function (string $parent, $fields) use ($mockData) {
                $mockData->parent = $parent;
                $mockData->fields = $fields;
                return null;
            });
        $http->method(strtolower($method))
        ->willReturnCallback(function (string $path, $parameters) use ($method, $callback, $mockData) {
            $request = (new Request())
            ->setParent($mockData->parent)
            ->setFields($mockData->fields)
            ->setAction($path);
            $parent = $request->getParent();
            $request = $request->getParameters($method, $parameters);
            $body = match($method) {
                'GET' => file_get_contents(__DIR__ . '/fixtures/' . $path . '.json'),
                default => '{}'
            };
            $params = json_decode($request);
            $headers = [
                'Status' => match ($method) {
                    'POST' => 201,
                    default => 200,
                },
                'Method' => $method,
                'X-Action' => $path,
                'X-Parent' => $parent,
                'X-Request' => json_encode($params, JSON_PRETTY_PRINT),
                'X-Params' => match ($method) {
                    'POST' => $params->$parent ?? null,
                    'PUT' => $params->$parent ?? null,
                    default => $params,
                }
            ];
            if ($method === 'POST' && !in_array($parent, ['rates'])) {
                $headers['id'] = 10;
            }
            if ($callback !== null) {
                $callback->call($this, $headers, $body);
            }
            return (new Response)->parse($body, $headers);
        });

        return new $className($http);
    }
}
