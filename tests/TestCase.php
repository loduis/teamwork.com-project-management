<?php

namespace TeamWorkPm\Tests;

use Closure;
use CURLFile;
use TeamWorkPm\Factory;
use TeamWorkPm\Request\JSON as Request;
use TeamWorkPm\Response\JSON as Response;
use TeamWorkPm\Rest\Client as HttpClient;
use Spatie\Snapshots\MatchesSnapshots;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    use MatchesSnapshots;

    protected function factory(string $className, Closure | array $callback = null)
    {

        [, $url, $key] = Factory::resolve($className);
        $http = $this->getMockBuilder(HttpClient::class)
            ->setConstructorArgs([$url, $key])
            ->getMock();


        $request = new Request();

        /*
        $http->expects($this->atLeastOnce())

            ->method('configRequest')
            ->willReturnCallback(function (string $parent, $fields) use ($request) {
                $request->setParent($parent)
                ->setFields($fields);
            });
        */
        $http->expects($this->any())
            ->method('notUseFields')->willReturnCallback(function () use ($http, $request) {
                $request->notUseFields();
                return $http;
            });

        foreach(['GET', 'POST', 'PUT', 'DELETE'] as $method) {
            $http
            ->expects($this->any())
            ->method(strtolower($method))
            ->willReturnCallback(function (string $path, mixed $parameters = null, array $opts = []) use ($method, $callback, $request) {
                if (false && $method === 'GET') {
                    echo $method . ' PATH: ', $path, PHP_EOL;
                }
                $request->setOpts($opts);
                $request->setAction($path);
                $parent = $request->getParent();
                $request = $request->getParameters($method, $parameters);
                $body = match($method) {
                    'GET' => file_get_contents(
                        __DIR__ . '/fixtures/' . $path . '.json'
                    ),
                    default => '{"STATUS": "OK"}'
                };
                if ($method === 'GET' && $request) {
                    $path .= '?' . $request;
                }
                $isFile = $method === 'POST' && is_array($parameters) && isset($parameters['file']) && $parameters['file'] instanceof CURLFile;
                $params =  $isFile ? $parameters : json_decode($request);
                $getRequest = function () use ($params, $parent, $isFile) {
                    if ($isFile) {
                        return $params;
                    }
                    $data = $params->$parent ?? $params;
                    if ($data !== null && count((array)$params) > 1) {
                        $data = $params;
                    }
                    return $data;
                };
                $headers = [
                    'Status' => match ($method) {
                        'POST' => 201,
                        default => 200,
                    },
                    'Method' => $method,
                    'X-Action' => $path,
                    'X-Parent' => $parent,
                    'X-Request' => json_encode($params, JSON_UNESCAPED_SLASHES),
                    'X-Params' => match ($method) {
                        'POST' => $getRequest(),
                        'PUT' => $getRequest(),
                        default => $params,
                    }
                ];
                if ($method === 'POST' && isset($params->$parent) && !in_array($parent, ['rates'])) {
                    $headers['id'] = 10;
                }
                if ($callback !== null) {
                    if ($callback instanceof Closure) {
                        $callback->call($this,
                            $headers['X-Request'],
                            $headers,
                            $body
                        );
                    } else {
                        $function = $callback["$method /$path"];
                        if (is_bool($function)) {
                            $function = fn() => null;
                        }
                        $res = $function->call($this,
                            $headers['X-Request'],
                            $headers,
                            $body
                        );
                        if ($res !== null) {
                            if (is_array($res)) {
                                if (array_is_list($res)) {
                                    [$headers, $body] = $res;
                                } else {
                                    $headers = $res;
                                }
                            } else {
                                $body = $res;
                            }
                        }
                    }
                }

                return (new Response)->parse($body, $headers);
            });
        }

        /** @disregard P1006 */

        return Factory::build($className, $http);
    }
}
