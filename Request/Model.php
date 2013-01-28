<?php
namespace TeamWorkPm\Request;

abstract class Model
{
    protected $method = null;
    protected $action = null;
    protected $parent = null;
    protected $fields = array();

    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    public function setFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }

    protected function getValue(& $field, & $options, array $parameters)
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
            $yes_no_boolean = array(
                'welcome_email_message',
                'send_welcome_email',
                'receive_daily_reports',
                'notes',
                'auto_give_project_access'
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
        if ($this->method == 'POST' && $options['required']) {
            if ($isNull) {
                throw new \TeamWorkPm\Exception('Required field ' . $field);
            }
        }
        //verficando campos que debe cumplir ciertos valores
        if (!$isNull && isset($options['validate']) &&
                        !in_array($value, $options['validate'])) {
                throw new \TeamWorkPm\Exception('Invalid value for field ' .
                                                            $field);
        }
        // @todo Ojo la gente de team work no mainten constante el formato name-other
        if (isset($camelize[$field])) {
            if ($field === 'open_id') {
                $field = 'openID';
            } else {
                $field = self::camelize($field);
            }
        } elseif (!isset($preserve[$field])) {
            if ($field === 'company_id') {
                if ($this->action === 'projects') {
                    $field = self::camelize($field);
                }
            } else {
                $field = self::dasherize($field);
            }
        }
        return $value;
    }

    protected function actionInclude($value)
    {
        return false !== strrpos($this->action, $value);
    }

    protected function getParent()
    {
        return $this->parent . ($this->actionInclude('/reorder') ? 's' : '');
    }

    protected static function camelize($string)
    {
        return preg_replace('/_(.)/e','strtoupper(\'$1\');', $string);
    }

    protected static function dasherize($string)
    {
        return str_replace('_', '-', $string);
    }

    public function getParameters($method, $parameters)
    {
        if ($parameters) {
            $this->method = $method;
            if ($method === 'GET') {
                if (is_array($parameters)) {
                    $parameters = http_build_query($parameters);
                }
            } elseif ($method === 'POST' || $method === 'PUT') {
                $parameters = $this->parseParameters($parameters);
            }
        } else {
            $parameters = null;
        }
        return $parameters;
    }

    /**
     * Return parameters for post and put request
     * @param array $parameters
     * @return string
     */
    abstract protected function parseParameters($parameters);
}