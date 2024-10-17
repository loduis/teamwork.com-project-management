<?php

namespace TeamWorkPm;

use CurlHandle;

final class Rest
{
    /**
     * @var string api format request an response
     */
    private static $FORMAT = 'json';

    /**
     * @var string this is the api key
     */
    private $key = null;

    /**
     * @var string your company name path
     */
    private $url = null;

    /**
     * @var \TeamWorkPm\Request\Model
     */
    private $request = null;

    /**
     * @param string $url
     * @param string $key
     * @throws \TeamWorkPm\Exception
     */
    public function __construct($url, $key)
    {
        if (empty($url) || empty($key)) {
            throw new Exception('Set your url and api key');
        }

        $this->key = $key;
        $this->url = $url;
        $format = strtoupper(self::$FORMAT);
        $request = '\TeamWorkPm\Request\\' . $format;
        $this->request = new $request();
    }

    /**
     * Call to api
     *
     * @param string $method
     * @param string $action
     * @param mixed $parameters
     * @return mixed
     * @throws \TeamWorkPm\Exception
     */
    private function execute($method, $action, $parameters = null)
    {
        $url = "{$this->url}$action." . self::$FORMAT;
        $headers = [
            'Authorization: BASIC ' . base64_encode($this->key . ':xxx'),
        ];
        $request = $this->request
            ->setAction($action)
            ->getParameters($method, $parameters);
        $ch = static::initCurl($method, $url, $request, $headers);
        $i = 0;
        while ($i < 5) {
            $data = curl_exec($ch);
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
        // echo $data, PHP_EOL, PHP_EOL;
        $body = substr($data, $header_size);
        $errorInfo = curl_error($ch);
        $error = curl_errno($ch);
        curl_close($ch);
        if ($error) {
            throw new Exception($errorInfo);
        }

        $headers['Status'] = $status;
        $headers['Method'] = $method;
        $headers['X-Url'] = $url;
        $headers['X-Request'] = $request;
        $headers['X-Action'] = $action;
        // for chrome use
        $headers['X-Authorization'] = 'BASIC ' . base64_encode($this->key . ':xxx');

        $responseClass = '\\TeamWorkPm\\Response\\' . strtoupper(self::$FORMAT);
        $response = new $responseClass();

        return $response->parse($body, $headers);
    }

    /**
     * @param string $method
     * @param string $url
     * @param string|null $params
     * @param array $headers
     * @return CurlHandle|false
     */
    private static function initCurl($method, $url, $params, $headers)
    {
        $ch = curl_init();
        switch ($method) {
            case 'GET':
                if (!empty($params)) {
                    $url .= '?' . $params;
                }
                break;
            case 'UPLOAD':
                curl_setopt_array($ch, [
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $params,
                ]);
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
                if ($params) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                }
                $headers = array_merge($headers, [
                    'Content-Type: application/' . self::$FORMAT,
                    'Content-Length:' . strlen($params),
                ]);
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
     * @param string $action
     * @param string|null $parameters
     *
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function get($action, $parameters = null)
    {
        return $this->execute('GET', $action, $parameters);
    }

    public function put($action, $parameters = null)
    {
        return $this->execute('PUT', $action, $parameters);
    }

    public function post($action, $parameters = null)
    {
        return $this->execute('POST', $action, $parameters);
    }

    public function delete($action)
    {
        return $this->execute('DELETE', $action, null);
    }

    public function upload($action, $parameters = null)
    {
        return $this->execute('UPLOAD', $action, $parameters);
    }

    /**
     * @return \TeamWorkPm\Request\Model|null
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function setFormat($value)
    {
        static $format = ['json', 'xml'];
        $value = strtolower($value);
        if (in_array($value, $format)) {
            self::$FORMAT = $value;
        }
    }

    private function parseHeaders($stringHeaders)
    {
        $headers = [];
        $stringHeaders = trim($stringHeaders);
        if ($stringHeaders) {
            $parts = explode("\n", $stringHeaders);
            foreach ($parts as $header) {
                $header = trim($header);
                if ($header && false !== strpos($header, ':')) {
                    list($name, $value) = explode(':', $header, 2);
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
}
