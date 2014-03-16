<?php namespace TeamWorkPm;

final class Rest
{
    /**
     *
     * @var string api format request an response
     */
    private static $FORMAT = 'json';

    /**
     * @var string this is the api key
     */
    private $key = null;

    /**
     * @var string this your company name path
     */
    private $company = null;

    /**
     * @var TeamWorkPm\Request\Model
     */
    private $request = null;

    /**
     *
     * @param string $company
     * @param string $key
     * @throws \TeamWorkPm\Exception
     */
    public function  __construct($company, $key)
    {
        if (empty($company) || empty($key)) {
            throw new Exception('Set your company and api key');
        } else {
            $this->key     = $key;
            $this->company = $company;
        }
        $format          = strtoupper(self::$FORMAT);
        $request         = '\TeamWorkPm\Request\\' . $format;
        $this->request  = new $request;
    }

    /**
     * Call to api
     *
     * @param string $method
     * @param string $action
     * @param mixed $request
     * @return mixed
     * @throws \TeamWorkPm\Exception
     */
    private function execute($method, $action, $request = null)
    {
        $url = 'http://'. $this->company . '.teamworkpm.net/'. $action .
                                                        '.' . self::$FORMAT;
        $headers = ['Authorization: BASIC '. base64_encode(
            $this->key . ':xxx'
        )];
        $request = $this->request
                        ->setAction($action)
                        ->getParameters($method, $request);
        $ch = curl_init();
        switch ($method) {
            case 'GET':
                if (!empty($request)) {
                    $url .= '?' . $request;
                }
                break;
            case 'UPLOAD':
                curl_setopt_array( $ch, [
                    CURLOPT_POST       => true,
                    CURLOPT_POSTFIELDS => $request
                ]);
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                break;
            case 'PUT':
            case 'POST':
                if ($method === 'POST') {
                    curl_setopt( $ch, CURLOPT_POST, true);
                } else {
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                }
                if ($request) {
                    curl_setopt( $ch, CURLOPT_POSTFIELDS, $request);
                }
                $headers = array_merge($headers, [
                    'Content-Type: application/' . self::$FORMAT,
                    'Content-Length:' . strlen($request)
                ]);
                break;
        }
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $i = 0;
        while ($i < 5) {
            $data        = curl_exec ($ch);
            $status      = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $headers     = $this->parseHeaders(substr($data, 0, $header_size));
            if ($status === 400 &&
                                (int) $headers['X-RateLimit-Remaining'] === 0) {
                $i ++;
                sleep(10);
            } else {
                break;
            }
        }

        $body        = substr($data, $header_size);

        $errorInfo   = curl_error($ch);
        $error       = curl_errno($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception($errorInfo);
        }

        $headers['Status'] = $status;
        $headers['Method'] = $method;
        $headers['X-Url']  = $url;
        $headers['X-Request'] = $request;
        $headers['X-Action']  = $action;
        // for chrome use
        $headers['X-Authorization'] = 'BASIC '. base64_encode($this->key . ':xxx');
        $response = '\TeamWorkPm\Response\\' . strtoupper(self::$FORMAT);
        $response = new $response;
        return $response->parse($body, $headers);
    }

    /**
     * Shortcut call get method to api
     *
     * @param string $action
     * @param mixed $request
     * @return TeamWorkPm\Response\Model
     */
    public function get($action, $request = null)
    {
        return $this->execute('GET', $action, $request);
    }

    public function put($action, $request = null)
    {
        return $this->execute('PUT', $action, $request);
    }

    public function post($action, $request = null)
    {
        return $this->execute('POST', $action, $request);
    }

    public function delete($action)
    {
        return $this->execute('DELETE', $action, null);
    }

    public function upload($action, $request = null)
    {
        return $this->execute('UPLOAD', $action, $request);
    }

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
                    $name  = trim($name);
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