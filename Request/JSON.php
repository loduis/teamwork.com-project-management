<?php
namespace TeamWorkPm\Request;

class JSON extends Model
{
    protected function _getParameters($parameters)
    {
        if (!empty($parameters) && is_array($parameters)) {
            $object = new \stdClass();
            $parent = $this->_getParent();
            if (($wrapper = $this->_getWrapper())) {
                $object->$wrapper = new \stdClass();
                $object->$wrapper->$parent = new \stdClass();
                $parent = $object->request->$parent;
            } else {
                $object->$parent = new \stdClass();
                $parent = $object->$parent;
            }

            if ($this->_actionInclude('/reorder')) {
                foreach ($parameters as $id) {
                    $item = new \stdClass();
                    $item->id = $id;
                    $parent->{$this->_parent}[] = $item;
                }
            } else {
                foreach ($this->_fields as $field=>$options) {
                    $value   = $this->_getValue($field, $options, $parameters);
                    if (isset ($options['attributes'])) {
                        foreach ($options['attributes'] as $name=>$type) {
                            $this->_setDefaultValueIfIsNull($type, $value);
                            if (NULL !== $value) {
                                if ($name === 'type') {
                                    if ($type === 'array') {

                                    } else {
                                        settype($value, $type);
                                    }
                                }
                            }
                        }
                    }
                    if (NULL !== $value) {
                        !empty($options['sibling']) ?
                            $object->$field = $value :
                            $parent->$field = $value;
                    }
                }
            }
            $parameters =  json_encode($object);
        } else {
            $parameters = NULL;
        }

        return $parameters;

    }

    protected function _getWrapper()
    {
        return ($this->_method == 'post' && $this->_actionInclude('/posts')) ? 'request' : NULL;
    }
}
