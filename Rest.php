<?php

final class TeamWorkPm_Rest
{
    private static $_REQUEST_FORMAT = 'json';
    private static  $_RESPONSE_FORMAT = 'json';

    private static
        $_instances;

    private
        $_key,
        $_company,
        $_errors,
        $_method,
        $_request,
        $response,
        $_isGET;

    private function  __construct($company, $key)
    {
        if (is_null($company) || is_null($key)) {
            throw new TeamWorkPm_Exception('set your company and api key');
        } else {
            $this->_key     = $key;
            $this->_company = $company;
        }

        $request  = 'TeamWorkPm_Request_' . strtoupper(self::$_RESPONSE_FORMAT);
        $response = 'TeamWorkPm_Response_' . strtoupper(self::$_RESPONSE_FORMAT);
        $this->_request  = new $request;
        $this->_reponse  = new $response;
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
        $url = 'http://'. $this->_company . '.teamworkpm.net/'. $action . '.' . self::$_REQUEST_FORMAT;
        $headers = array('Authorization: BASIC '. base64_encode($this->_key . ':xxx'));
        $method = str_replace('_', '', $_method);
        $request = $this->_request->$method($request);
        $this->_isGET = false;
        switch ($method) {
            case 'get':
                if (null !== $request) {
                    $url .= '?' . $request;
                }
                $this->_isGET = true;
                break;
            case 'put':
            case 'post':
                $headers = array_merge($headers, array(
                    'Content-Type: application/' . self::$_REQUEST_FORMAT
                ));
                break;
        }
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);

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
        $errorInfo = curl_error($ch);
        $error = curl_errno($ch);
        curl_close($ch);
        $response = FALSE;
        if ($this->_isGET) {
            $headers = array('status'=>$status);
            if (!$error) {
                $headers['errors'] = $errorInfo;
            }
            $response = $this->_reponse->parse($data, array('status'=>$status));
        } elseif ($this->_isSuccess($status)) {
            $response = TRUE;
        }
        return $response;

    }

    private function _isSuccess($status)
    {
        return in_array($status, array(200, 201));
    }

    public function  __get($name)
    {
        if ('request' == $name) {
            return $this->_request;
        }
    }

    public static function setRequestFormat($value)
    {
        self::$_REQUEST_FORMAT = $value;
    }

    public static function setResponseFormat($value)
    {
        self::$_RESPONSE_FORMAT = $value;
    }
}