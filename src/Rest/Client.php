<?php

declare(strict_types = 1);

namespace TeamWorkPm\Rest;

use CURLFile;
use CurlHandle;
use Exception;
use TeamWorkPm\Rest\Request\Model as Request;
use TeamWorkPm\Rest\Response\Model as Response;

class Client
{
    /**
     * @var string api format request an response
     */
    private static string $FORMAT = 'json';

    /**
     * @var string this is the api key
     */
    private string $key;

    /**
     * @var string your company name path
     */
    private string $url;

    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var Response
     */
    private Response $response;

    /**
     * @param string $url
     * @param string $key
     * @throws Exception
     */
    public function __construct($url, $key)
    {
        if (empty($url) || empty($key)) {
            throw new Exception('Set your url and api key');
        }

        $this->key = $key;
        $this->url = $url;
        $format = strtoupper(self::$FORMAT);
        $request =  '\\' . __NAMESPACE__ . '\Request\\' . $format;
        $response = '\\' . __NAMESPACE__ . '\Response\\' . $format;
        /** @psalm-suppress PropertyTypeCoercion */
        $this->request = new $request();
        /** @psalm-suppress PropertyTypeCoercion */
        $this->response = new $response();
    }

    /**
     * Call to api
     *
     * @param string $method
     * @param string $path
     * @param mixed $parameters
     * @return mixed
     * @throws Exception
     */
    private function request(string $method, string $path, $parameters = null, array $opts = []): bool | int | string | Response
    {
        $url = "{$this->url}$path." . static::$FORMAT;
        $headers = [
            'Authorization: BASIC ' . base64_encode($this->key . ':xxx'),
        ];
        $request = $this->request
            ->setOpts($opts)
            ->setAction($path);
        $useFiles = $request->useFiles();

        $ch = static::initCurl(
            $method,
            $url,
            $request = $request->getParameters($method, $parameters),
            $headers
        );

        $i = 0;
        $data = '';
        $headers = [];
        $header_size = 0;
        $status = 0;
        while ($i < 5) {
            $data = (string) curl_exec($ch);
            $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $headers = $this->parseHeaders(substr($data, 0, $header_size));
            if ($status === 400 && (int)$headers['x-ratelimit-remaining'] === 0) {
                $i++;
                $reset = $headers['x-ratelimit-reset'];
                sleep($reset);
            } else {
                break;
            }
        }
        // print_r($headers);
        // echo $request, PHP_EOL;
        // echo $data, PHP_EOL, PHP_EOL;
        $body = substr($data, $header_size);
        $errorInfo = curl_error($ch);
        $error = curl_errno($ch);
        curl_close($ch);
        if ($error) {
            throw new Exception($errorInfo);
        }

        if ($status === 204) {
            $body = '{}';
        }

        $headers['Status'] = $status;
        $headers['Method'] = $method;
        $headers['X-Url'] = $url;
        $headers['X-Request'] = $request;
        $headers['X-Action'] = $path;
        $headers['X-Parent'] = $this->request->getParent();
        $headers['X-Not-Use-Files'] = !$useFiles;
        // for chrome use
        // $headers['X-Authorization'] = 'BASIC ' . base64_encode($this->key . ':xxx');
        // print_r($headers);

        return $this->response->parse($body, $headers);
    }

    /**
     * @param string $method
     * @param string $url
     * @param string $params
     * @param array $headers
     * @return CurlHandle
     */
    private static function initCurl(string $method, string $url, null|string|array $params, array $headers): CurlHandle
    {
        $ch = curl_init();
        switch ($method) {
            case 'GET':
                if ($params !== null && !empty($params)) {
                    $url .= '?' . $params;
                }
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                break;
            case 'PUT':
            case 'POST':
                if ($method === 'POST') {
                    curl_setopt($ch, CURLOPT_POST, true);
                } else {
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                }
                if ($params !== null && !empty($params)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                }
                if (is_array($params) && isset($params['file']) && $params['file'] instanceof CURLFile) {
                    $headers[] = 'Content-Type: multipart/form-data';
                } else {
                    $headers = array_merge($headers, [
                        'Content-Type: application/' . self::$FORMAT,
                        'Content-Length:' . strlen((string) $params),
                    ]);
                }
                break;
        }
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        return $ch;
    }

    /**
     * Shortcut call get method to api
     *
     * @param string $path
     * @param object|array|null $parameters
     *
     * @return Response
     * @throws Exception
     */
    public function get(string $path, object|array|null $parameters = null, array $opts = []): Response
    {
        return $this->request('GET', $path, $parameters, $opts);
    }

    public function put(string $path, object|array|null $parameters = null, array $opts = []): bool | int | Response
    {
        return $this->request('PUT', $path, $parameters, $opts);
    }

    public function post(string $path, object|array|null $parameters = null, array $opts = []): bool | int | string | Response
    {
        return $this->request('POST', $path, $parameters, $opts);
    }

    public function delete(string $path, array $opts = []): bool
    {
        return $this->request('DELETE', $path, null, $opts);
    }

    /*
    public function configRequest(string $parent, array $fields = []): void
    {
        $this->request->setParent($parent)
            ->setFields($fields);
    }
    */

    /**
     * @return Request|null
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function setFormat(string $value): void
    {
        static $format = ['json', 'xml'];
        $value = strtolower($value);
        if (in_array($value, $format)) {
            static::$FORMAT = $value;
        }
    }

    private function parseHeaders(string $stringHeaders): array
    {
        $headers = [];
        $stringHeaders = trim($stringHeaders);
        if ($stringHeaders) {
            $parts = explode("\n", $stringHeaders);
            foreach ($parts as $header) {
                $header = trim($header);
                if ($header && str_contains($header, ':')) {
                    /** @psalm-suppress PossiblyUndefinedArrayOffset */
                    [$name, $value] = explode(':', $header, 2);
                    $value = trim($value);
                    $name = trim($name);
                    if (isset($headers[$name])) {
                        if (is_array($headers[$name])) {
                            $headers[$name][] = $value;
                        } else {
                            $_val = $headers[$name];
                            $headers[$name] = [$_val, $value];
                        }
                    } else {
                        $headers[$name] = $value;
                    }
                }
            }
        }

        return $headers;
    }

    public function notUseFields()
    {
        $this->request->notUseFields();

        return $this;
    }
}
