<?php

final class TeamWorkPm_Rest
{
    const FORMAT = 'json';

    private static
        $_instances;

    private
        $_key,
        $_company,
        $_errors,
        $_method;

    private function  __construct($company, $key)
    {
        if (is_null($company) || is_null($key)) {
            throw new TeamWorkPm_Exception('set your company and api key');
        } else {
            $this->_key     = $key;
            $this->_company = $company;
        }
    }

    public static function getInstance($company, $key)
    {
        $hash = md5($company . '-' . $key);
        if (null === self::$_instances[$hash]) {
            self::$_instances[$hash] = new self($company, $key);
        }

        return self::$_instances[$hash];
    }

    protected function _execute($method, $action, $request = null)
    {
        $url = "http://". $this->_company .".teamworkpm.net/". $action . '.' . self::FORMAT;
        $headers = array( "Authorization: BASIC ". base64_encode($this->_key .":xxx" ));
        switch ($method) {
            case '_get':
                if (null !== $request) {
                    if (is_array($request)) {
                        $request = $this->_getParametersAsString($request);
                    }
                    $url .= '?' . $request;
                }
                break;
            case '_put':
            case '_post':
                $headers = array_merge($headers, array(
                    'Content-Type: application/' . self::FORMAT
                ));
                break;
        }
        $this->_method = strtoupper(str_replace('_', '', $method));
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);

        return $this->$method($ch, $request);
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
            @list($action, $request) = $arguments;
            return $this->_execute($name, $action, $request);
        }
        return null;
    }

    private function _get($ch, $request = null)
    {
        return $this->_response($ch);
    }

    private function _put($ch, $request)
    {
        $length = strlen($request);
        if ($length) {
            $f = fopen('php://temp', 'rw');
            fwrite($f, $request);
            rewind($f);
            curl_setopt($ch, CURLOPT_INFILE, $f);
            curl_setopt($ch, CURLOPT_INFILESIZE, $length);
        }
        curl_setopt($ch, CURLOPT_PUT, true);
        $response = $this->_response($ch);
        if (isset ($f)) {
            fclose($f);
        }
        return $response;
    }

    private function _post($ch, $request)
    {
        curl_setopt( $ch, CURLOPT_POST, true );
        if ($request) {
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $request);
        }
        return $this->_response($ch);
    }

    private function _delete($ch, $request = null)
    {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        return $this->_response($ch);
    }

    private function _response($ch)
    {
        $data = curl_exec ( $ch );
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->_errors = curl_error($ch);
        $error = curl_errno($ch);
        curl_close($ch);
        $response = false;
        if (!$error) {
            if (!$this->_isSuccess($status)) {
                $this->_errors .= "\n"  . $data;
                if ($this->_isGET()) {
                    $response = array();
                }
            } else {
                $method = '_response' . strtoupper(self::FORMAT);
                $response =  $this->$method($data);
            }
        }

        return $response;

    }

    private function _responseXML($data)
    {
        libxml_use_internal_errors(true);
        $response = simplexml_load_string($data);
        if ($response instanceof SimpleXMLElement) {
            if (!$this->_isGET() && ($response[0] == 'OK' || $response[0] == 'Created')) {
                $response = true;
            }
        }

        return $response;
    }

    private function _responseJSON($data)
    {
        $response = json_decode($data, true);
        if ($this->_isGET()) {
            unset ($response['status']);
        } else {
            $response = true;
        }

        return $response;
    }

    private function _isSuccess($status)
    {
        return in_array($status, array(200, 201));
    }

    private function _isGET()
    {
        return $this->_method == 'GET';
    }

    private function _getParametersAsString(array $parameters)
    {
        $queryParameters = array();
        foreach ($parameters as $key => $value) {
            $queryParameters[] = $key . '=' . $this->_urlencode($value);
        }
        return implode('&', $queryParameters);
    }

    private function _urlencode($value)
    {
        return urlencode($value);
    }

    public function getErrors()
    {
        return $this->_errors;
    }
}