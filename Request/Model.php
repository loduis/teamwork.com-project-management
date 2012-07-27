<?php

abstract class TeamWorkPm_Request_Model
{
    protected $_method;
    protected $_action;
    protected $_parent;
    protected $_fields;

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

    /**
     * Return parameters for rest request
     * @param mixed $parameters
     * @return string
     */
    public function get($parameters = null)
    {
        if (is_array($parameters)) {
            $parameters = http_build_query($parameters);
        }

        return $parameters;
    }

    public function post($parameters)
    {
        $this->_method = 'post';
        return $this->_getParameters($parameters);
    }

    public function put($parameters)
    {
        $this->_method = 'put';
        return $this->_getParameters($parameters);
    }

    public function delete()
    {
        return NULL;
    }

    protected function _getValue(& $field, & $options, array $parameters)
    {
        static
            $camelize = array(
                'pending_file_attachments'=>TRUE,
                'date_format'=>TRUE,
                'send_welcome_email'=>TRUE,
                'receive_daily_reports'=>TRUE,
                'welcome_email_message'=>TRUE,
                'auto_give_project_access'=>TRUE,
                'open_id'=>TRUE,
                'user_language'=>TRUE
            ),
            $preserve = array(
                'address_one'=>TRUE,
                'address_two'=>TRUE
            )
          ;

        $value = isset($parameters[$field]) ? $parameters[$field] : NULL;
        // @todo Ojo la gente de team work no mainten constante el formato name-other
        if (isset($camelize[$field])) {
            if ($field === 'open_id') {
                $field = 'openID';
            } elseif ($field === 'company_id') {
                $field = $this->_actionInclude('/people') ?
                    self::_dasherize($field) :
                    self::_camelize($field);
            } else {
                $field = self::_camelize($field);
            }
        } elseif (!isset($preserve[$field])) {
            $field = self::_dasherize($field);
        }
        if (!is_array($options)) {
            $options = array('required'=>$options, 'attributes'=> array());
        }
        $isNull =  NULL === $value;
        //verficando campos requeridos
        if ($this->_method == 'post' && $options['required'] && $isNull) {
            throw new TeamWorkPm_Exception('The field ' . $field . ' is required ');
        }
        //verficando campos que debe cumplir ciertos valores
        if (isset($options['validate']) && !$isNull && !in_array($value, $options['validate'])) {
                throw new TeamWorkPm_Exception('Invalid value for the field ' . $field);
        }

        return $value;
    }

    protected function _setDefaultValueIfIsNull($type, &$value)
    {
        if (strpos($type, '=') !== FALSE) {
            list($type, $default) = explode('=', $type);
        } else {
            $default = NULL;
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
        return FALSE !== strrpos($this->_action, $value);
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

    /**
     * Return parameters for post and put request
     * @param array $parameters
     * @return string
     */
    abstract protected function _getParameters($parameters);

    abstract protected function _getWrapper();
}