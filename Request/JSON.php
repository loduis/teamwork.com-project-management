<?php

class TeamWorkPm_Request_JSON extends TeamWorkPm_Request_Model
{
    protected function _getParameters($parameters)
    {
        if (is_array($parameters)) {
            $object = new stdClass();
            $parent = $this->_getParent();
            if ($wrapper = $this->_getWrapper()) {
                $object->$wrapper = new stdClass();
                $object->$wrapper->$parent = new stdClass();
                $parent = $object->request->$parent;
            } else {
                $object->$parent = new stdClass();
                $parent = $object->$parent;
            }

            if ($this->_isActionReorder()) {
                foreach ($parameters as $id) {
                    $item = new stdClass();
                    $item->id = $id;
                    $parent->{$this->_parent}[] = $item;
                }
            } else {
                foreach ($this->_fields as $field=>$options) {
                    $value   = $this->_getValue($field, $options, $parameters);
                    if (isset ($options['attributes'])) {
                        foreach ($options['attributes'] as $name=>$type) {
                            $this->_setDefaultValueIfIsNull($type, $value);
                            if (null !== $value) {
                                if ($name == 'type') {
                                    if ($type == 'array') {

                                    } else {
                                        settype($value, $type);
                                    }
                                }
                            }
                        }
                    }
                    if (null !== $value) {
                        $parent->$field = $value;
                    }
                }
            }

            $parameters =  json_encode($object);
        }

        return $parameters;

    }

    protected function _getWrapper()
    {
      return ($this->_method == 'post' && $this->_action == 'posts') ? 'request' : '';
    }
}
