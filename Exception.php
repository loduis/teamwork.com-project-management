<?php

class TeamWorkPm_Exception extends ErrorException
{
    private $_response = NULL;
    private $_headers = array();

    public function  __construct($errorInfo)
    {
        if (!is_array($errorInfo)) {
            $message              = $errorInfo;
            $errorInfo = array();
            $errorInfo['Message'] = $message;
        }
        $this->message = $errorInfo['Message'];
        if (isset($errorInfo['Response'])) {
            $this->_response    = $errorInfo['Response'];
        }
        if (isset($errorInfo['Headers'])) {
            $this->_headers = $errorInfo['Headers'];
        }

    }

    public function getResponse()
    {
        return $this->_response;
    }

    public function getHeaders()
    {
        return $this->_headers;
    }
}