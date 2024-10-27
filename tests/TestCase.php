<?php

namespace TeamWorkPm\Tests;

use Closure;
use TeamWorkPm\Factory;
use TeamWorkPm\Request\JSON as Request;
use TeamWorkPm\Response\JSON as Response;
use TeamWorkPm\Rest\Client as HttpClient;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function factory(string $className, Closure | array $callback = null)
    {
        [$className, $url, $key] = Factory::resolve($className);
        $http = $this->getMockBuilder(HttpClient::class)
            ->setConstructorArgs([$url, $key])
            ->getMock();

        /** @disregard P1006 */
        Factory::shareHttpClient($url, $key, $http);

        $request = new Request();

        $http->expects($this->atLeastOnce())
            ->method('configRequest')
            ->willReturnCallback(function (string $parent, $fields) use ($request) {
                $request->setParent($parent)
                ->setFields($fields);
            });

        foreach(['GET', 'POST', 'PUT', 'DELETE'] as $method) {
            $http
                ->expects($this->any())
                ->method(strtolower($method))
                ->willReturnCallback(function (string $path, mixed $parameters = null) use ($method, $callback, $request) {
                    $request->setAction($path);
                    $parent = $request->getParent();
                    $request = $request->getParameters($method, $parameters);
                    $body = match($method) {
                        'GET' => file_get_contents(
                            __DIR__ . '/fixtures/' . $path . '.json'
                        ),
                        default => '{"STATUS": "OK"}'
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
                        if ($callback instanceof Closure) {
                            $callback->call($this, $headers, $body);
                        } else {
                            $res = $callback["$method /$path"]($headers, $body);
                            if ($res !== null) {
                                if (array_is_list($res)) {
                                    [$headers, $body] = $res;
                                } elseif (is_array($res)) {
                                    $headers = $res;
                                } else {
                                    $body = $res;
                                }
                            }
                        }
                    }
                    return (new Response)->parse($body, $headers);
                });
        }

        return new $className($http);
    }
}
