<?php

abstract class TeamWorkPm_Request_Model
{
    protected $_method;
    protected $_action;
    protected $_parent;
    protected $_fields;

    /**
     * Return parameters for post and put request
     * @param string $name [post, put]
     * @param array $arguments
     * @return string
     */
    public function  __call($name, array $arguments)
    {
        switch ($name) {
            case 'post':
            case 'put':
                $this->_method = $name;
                return call_user_func_array(array($this, '_getParameters'), $arguments);
            case 'setAction':
            case 'setParent':
            case 'setFields':
                $property = strtolower(str_replace('set', '_', $name));
                $this->$property = $arguments[0];
                return $this;

        }
    }

    /**
     * Return parameters for rest request
     * @param mixed $parameters
     * @return string
     */
    public function get($parameters = null)
    {
        if (is_array($parameters)) {
            $queryParameters = array();
            foreach ($parameters as $key => $value) {
                $queryParameters[] = $key . '=' . urlencode($value);
            }
            $parameters =  implode('&', $queryParameters);
        }

        return $parameters;
    }

    protected function _getValue(& $field, & $options, array $parameters)
    {
        $value = isset($parameters[$field]) ? $parameters[$field] : null;
        $field = str_replace('_', '-', $field);
        if (!is_array($options)) {
            $options = array('required'=>$options, 'attributes'=> array());
        }
        $isNull =  null === $value;
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
        @list($type, $default) = explode('=', $type);
        if (is_null($value) && null !== $default) {
            if ($default == 'false') {
                $default = false;
            } elseif ($default == 'true') {
                $default = true;
            }
            $value = $default;
        }
    }

    protected function _isActionReorder()
    {
        return $this->_action == 'reorder';
    }

    protected function _getParent()
    {
        return $this->_parent . ($this->_isActionReorder() ? 's' : '');
    }
    /**
     * Return parameters for post and put request
     * @param array $parameters
     * @return string
     */
    abstract protected function _getParameters($parameters);

    abstract protected function _getWrapper();
}