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
                'pending_file_attachments'
            ),
            $preserve = array(
                'address_one',
                'address_two'
            )
          ;

        $value = isset($parameters[$field]) ? $parameters[$field] : NULL;
        // @todo Ojo la gente de team work no mainten constante el formato name-other
        if (!in_array($field, $preserve)) {
            if (in_array($field, $camelize)) {
                $field = preg_replace('/_(.)/e','strtoupper(\'$1\');', $field);
            } else {
                $field = str_replace('_', '-', $field);
            }
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

    /**
     * Return parameters for post and put request
     * @param array $parameters
     * @return string
     */
    abstract protected function _getParameters($parameters);

    abstract protected function _getWrapper();
}