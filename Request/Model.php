<?php

abstract class TeamWorkPm_Request_Model
{
    protected $_method = null;
    protected $_action = null;
    protected $_parent = null;
    protected $_fields = null;

    public function setParent($parent)
    {
        $this->_parent = $parent;
        return $this;
    }

    public function setAction($action)
    {
        $this->_action = $action;
        return $this;
    }

    public function setFields(array $fields)
    {
        $this->_fields = $fields;
        return $this;
    }

    protected function _getValue(& $field, & $options, array $parameters)
    {
        static
            $camelize = array(
                'pending_file_attachments' => true,
                'date_format'              => true,
                'send_welcome_email'       => true,
                'receive_daily_reports'    => true,
                'welcome_email_message'    => true,
                'auto_give_project_access' => true,
                'open_id'                  => true,
                'user_language'            => true,
                'pending_file_ref'         => true,
                'new_company'              => true
            ),
            $preserve = array(
                'address_one' => true,
                'address_two' => true
            );
        $value = isset($parameters[$field]) ? $parameters[$field] : null;
        if (!is_array($options)) {
            $options = array('required'=>$options, 'attributes'=> array());
        }
        $isNull =  null === $value;
        //verficando campos requeridos
        if ($this->_method == 'POST' && $options['required'] && $isNull) {
            throw new TeamWorkPm_Exception('The field ' . $field . ' is required ');
        }
        //verficando campos que debe cumplir ciertos valores
        if (isset($options['validate']) && !$isNull && !in_array($value, $options['validate'])) {
                throw new TeamWorkPm_Exception('Invalid value for the field ' . $field);
        }
        // @todo Ojo la gente de team work no mainten constante el formato name-other
        if (isset($camelize[$field])) {
            if ($field === 'open_id') {
                $field = 'openID';
            } else {
                $field = self::_camelize($field);
            }
        } elseif (!isset($preserve[$field])) {
            if ($field === 'company_id') {
                if ($this->_action === 'projects') {
                    $field = self::_camelize($field);
                }
            } else {
                $field = self::_dasherize($field);
            }
        }
        return $value;
    }

    protected function _setDefaultValueIfIsNull($type, &$value)
    {
        if (strpos($type, '=') !== FALSE) {
            list($type, $default) = explode('=', $type);
        } else {
            $default = null;
        }
        if (is_null($value) && null !== $default) {
            if ($default == 'false') {
                $default = FALSE;
            } elseif ($default == 'true') {
                $default = TRUE;
            }
            $value = $default;
        }
    }

    protected function _actionInclude($value)
    {
        return false !== strrpos($this->_action, $value);
    }

    protected function _getParent()
    {
        return $this->_parent . ($this->_actionInclude('/reorder') ? 's' : '');
    }

    protected static function _camelize($string)
    {
        return preg_replace('/_(.)/e','strtoupper(\'$1\');', $string);
    }

    protected static function _dasherize($string)
    {
        return str_replace('_', '-', $string);
    }

    public function getParameters($method, $parameters)
    {
        if ($parameters) {
            $this->_method = $method;
            if ($method === 'GET') {
                if (is_array($parameters)) {
                    $parameters = http_build_query($parameters);
                }
            } elseif ($method === 'UPLOAD') {
                if (empty($parameters['file'])) {
                    throw new TeamWorkPm_Exception('Require field file');
                }
            } elseif ($method === 'POST' || $method === 'PUT') {
                $parameters = $this->_getParameters($parameters);
            }
        }
        return $parameters;
    }

    /**
     * Return parameters for post and put request
     * @param array $parameters
     * @return string
     */
    abstract protected function _getParameters($parameters);

    abstract protected function _getWrapper();
}