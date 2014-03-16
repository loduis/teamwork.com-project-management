<?php namespace TeamWorkPm;

class Exception extends \ErrorException
{
    private $response = null;
    private $headers = [];


    public function  __construct($errorInfo)
    {
        if (!is_array($errorInfo)) {
            $message              = $errorInfo;
            $errorInfo = [];
            $errorInfo['Message'] = $message;
        }
        $this->message = trim($errorInfo['Message']);
        if (isset($errorInfo['Response'])) {
            $this->response    = $errorInfo['Response'];
        }
        if (isset($errorInfo['Headers'])) {
            $this->headers = $errorInfo['Headers'];
        }
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}