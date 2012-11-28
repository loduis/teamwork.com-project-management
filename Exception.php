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
        $code = preg_replace('/\s\s+/', ' ', $code);
        $code = str_replace(array(' ', "'"), array('_', ''), $code);
        switch ($code) {
            case 'YOU_CANT_DELETE_THE_OWNER_COMPANY':
                echo 'SET CODE, ', "\n";
                $this->code = Error::CAN_NOT_DELETE_THIS_RESOURCE;
                break;
            case 'ALREADY_EXISTS':
            case 'PROJECT_NAME_TAKEN':
                $this->code = Error::THIS_RESOURCE_ALREADY_EXISTS;
                break;
            default:
                break;
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