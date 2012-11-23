<?php
namespace TeamWorkPm;

class Exception extends \ErrorException
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

        $code = strtoupper($this->message);
        $code = trim($code);
        $code = str_replace(' ', '_', $code);
        $constant = __NAMESPACE__ . "\\Error::" . $code;
        if (defined($constant) && ($code = constant($constant))) {
            $this->code = $code;
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