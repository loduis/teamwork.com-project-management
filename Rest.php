<?php

final class TeamWorkPm_Rest
{
    private static $_FORMAT = 'json';

    private static
        $_instances;

    private
        $_key,
        $_company,
        $_method,
        $_request;

    private function  __construct($company, $key)
    {
        if (is_null($company) || is_null($key)) {
            throw new TeamWorkPm_Exception('set your company and api key');
        } else {
            $this->_key     = $key;
            $this->_company = $company;
        }

        $request  = 'TeamWorkPm_Request_' . strtoupper(self::$_FORMAT);
        $this->_request  = new $request;
    }

    public static function getInstance($company, $key)
    {
        $hash = md5($company . '-' . $key);
        if (null === self::$_instances[$hash]) {
            self::$_instances[$hash] = new self($company, $key);
        }

        return self::$_instances[$hash];
    }

    protected function _execute($_method, $action, $request = null)
    {
        $url = 'http://'. $this->_company . '.teamworkpm.net/'. $action . '.' . self::$_FORMAT;
        $headers = array('Authorization: BASIC '. base64_encode($this->_key . ':xxx'));
        $method = str_replace('_', '', $_method);
        $this->_request->setAction($action);
        $request = $this->_request->$method($request);
        $this->_method = strtoupper(substr($_method, 1));
        switch ($method) {
            case 'get':
                if (!empty($request)) {
                    $url .= '?' . $request;
                }
                break;
            case 'put':
            case 'post':
                $headers = array_merge($headers, array(
                    'Content-Type: application/' . self::$_FORMAT,
                    'Content-Length:' . strlen($request)
                ));
                break;
        }
        echo "\nUrl: ", $url, "\n";
        echo 'Request: ', $request, "\n";
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HEADER         => TRUE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_HTTPHEADER     => $headers
        ));

        return $this->$_method($ch, $request);
    }

    /**
     * Ejecuta uno de los metodos descrito arriba
     * @param string $name [get,post,put]
     * @param array $arguments [action, request]
     * @return mixed
     */

    public function  __call($name, $arguments)
    {
        $name   = '_' . strtolower($name);
        if (method_exists($this, $name)) {
            if (count($arguments) < 2) {
                $arguments[] = NULL;
            }
            list($action, $request) = $arguments;
            return $this->_execute($name, $action, $request);
        }
        return null;
    }

    private function _get($ch)
    {
        return $this->_response($ch);
    }

    private function _put($ch, $request)
    {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        if ($request) {
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $request);
        }
        $response = $this->_response($ch);
        return $response;
    }

    private function _post($ch, $request)
    {
        curl_setopt( $ch, CURLOPT_POST, TRUE);
        if ($request) {
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $request);
        }
        return $this->_response($ch);
    }

    private function _upload($ch, $request)
    {
        return $this->_post($ch, $request);
    }

    private function _delete($ch)
    {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        return $this->_response($ch);
    }

    private function _response($ch)
    {
        $data        = curl_exec ($ch);
        //echo $data;
        $status      = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers     = substr($data, 0, $header_size);
        $body        = substr($data, $header_size);
        $errorInfo   = curl_error($ch);
        $error       = curl_errno($ch);
        curl_close($ch);
        if ($error) {
            throw new TeamWorkPm_Exception($errorInfo);
        }
        $headers           = $this->_parseHeaders($headers);
        $headers['Status'] = $status;
        $headers['Method'] = strtoupper($this->_method);
        $class = 'TeamWorkPm_Response_' . strtoupper(self::$_FORMAT);
        $parser  = new $class;

        return $parser->parse($body, $headers);


        /*
        $response = FALSE;
        if ($this->_isGET && $status === 200) {
        } elseif ($this->_isSuccess($status)) {
            $response = TRUE;
        } else {
            $errors = $data;
            if ($status === 422) {
                if (self::$_FORMAT === 'xml') {
                    libxml_use_internal_errors(TRUE);
                    $xml = simplexml_load_string($data);
                    $property = 0;
                    $errors = $xml->$property;
                } else {
                    $json = json_decode($data);
                    $errors = $json->MESSAGE;
                }
            }
            echo $errors, "\n";
            throw new TeamWorkPm_Exception($errors);
        }
        //echo '-------------------------------------', "\n\n";
        echo $data;
        return $response;*/
    }

    private function _isSuccess($status)
    {
        return in_array($status, array(200, 201));
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
                    $headers[$name] = trim($value);
                }
            }
        }

        return $headers;
    }
}