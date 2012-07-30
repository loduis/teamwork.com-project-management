<?php

final class TeamWorkPm_Rest
{
    /**
     *
     * @var string api format request an response
     */
    private static $_FORMAT = 'json';

    /**
     * @var string this is the api key
     */
    private $_key = NULL;

    /**
     * @var string this your company name path
     */
    private $_company = NULL;

    /**
     * @var TeamWorkPm_Request_Model
     */
    private $_request = NULL;

    /**
     * @var TeamWorkPm_Response_Model
     */
    private $_response = NULL;

    /**
     *
     * @param string $company
     * @param string $key
     * @throws TeamWorkPm_Exception
     */
    public function  __construct($company, $key)
    {
        if (empty($company) || empty($key)) {
            throw new TeamWorkPm_Exception('Set your company and api key.');
        } else {
            $this->_key     = $key;
            $this->_company = $company;
        }
        $format          = strtoupper(self::$_FORMAT);
        $request         = 'TeamWorkPm_Request_' . $format;
        $this->_request  = new $request;
        $response        = 'TeamWorkPm_Response_' . $format;
        $this->_response = new $response;
    }

    /**
     * Call to api
     *
     * @param string $method
     * @param string $action
     * @param mixed $request
     * @return mixed
     * @throws TeamWorkPm_Exception
     */
    protected function _execute($method, $action, $request = null)
    {
        $url = 'http://'. $this->_company . '.teamworkpm.net/'. $action . '.' . self::$_FORMAT;
        $headers = array('Authorization: BASIC '. base64_encode($this->_key . ':xxx'));
        $request = $this->_request
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
                curl_setopt_array( $ch, array(
                    CURLOPT_POST       => TRUE,
                    CURLOPT_POSTFIELDS => $request
                ));
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                break;
            case 'PUT':
            case 'POST':
                if ($method === 'POST') {
                    curl_setopt( $ch, CURLOPT_POST, TRUE);
                } else {
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                }
                if ($request) {
                    curl_setopt( $ch, CURLOPT_POSTFIELDS, $request);
                }
                $headers = array_merge($headers, array(
                    'Content-Type: application/' . self::$_FORMAT,
                    'Content-Length:' . strlen($request)
                ));
                break;
        }
        curl_setopt_array($ch, array(
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HEADER         => TRUE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_SSL_VERIFYPEER => FALSE
        ));
        $data        = curl_exec ($ch);
        $status      = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers     = $this->_parseHeaders(substr($data, 0, $header_size));
        $body        = substr($data, $header_size);
        $errorInfo   = curl_error($ch);
        $error       = curl_errno($ch);
        curl_close($ch);
        if ($error) {
            throw new TeamWorkPm_Exception($errorInfo);
        }
        $headers['Status'] = $status;
        $headers['Method'] = $method;
        $headers['X-Url']  = $url;
        $headers['X-Request'] = $request;

        return $this->_response->parse($body, $headers);
    }

    /**
     * Shortcut call get method to api
     *
     * @param string $action
     * @param mixed $request
     * @return TeamWorkPm_Response_Model
     */
    public function get($action, $request = NULL)
    {
        return $this->_execute('GET', $action, $request);
    }

    public function put($action, $request = NULL)
    {
        return $this->_execute('PUT', $action, $request);
    }

    public function post($action, $request = NULL)
    {
        return $this->_execute('POST', $action, $request);
    }

    public function delete($action)
    {
        return $this->_execute('DELETE', $action, NULL);
    }

    public function upload($action, $request = NULL)
    {
        return $this->_execute('UPLOAD', $action, $request);
    }

    public function getRequest()
    {
        return $this->_request;
    }

    public static function setFormat($value)
    {

        static $format = array('json', 'xml');
        $value = strtolower($value);
        if (in_array($value, $format)) {
            self::$_FORMAT = $value;
        }
    }

    private function _parseHeaders($stringHeaders)
    {
        $headers = array();
        $stringHeaders = trim($stringHeaders);
        if ($stringHeaders) {
            $parts = explode("\n", $stringHeaders);
            foreach ($parts as $header) {
                $header = trim($header);
                if ($header && FALSE !== strpos($header, ':')) {
                    list($name, $value) = explode(':', $header, 2);
                    $value = trim($value);
                    $name  = trim($name);
                    if (isset($headers[$name])) {
                        if (is_array($headers[$name])) {
                            $headers[$name][] = $value;
                        } else {
                            $_val = $headers[$name];
                            $headers[$name] = array($_val, $value);
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