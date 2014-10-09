<?php namespace TeamWorkPm\Request;

use \stdClass;

class JSON extends Model
{
    protected function parseParameters($parameters)
    {
        if (!empty($parameters) && is_array($parameters)) {
            $object = new stdClass();
            $parent = $this->getParent();
            $object->$parent = new stdClass();
            $parent = $object->$parent;

            if ($this->actionInclude('/reorder')) {
                foreach ($parameters as $id) {
                    $item = new stdClass();
                    $item->id = $id;
                    $parent->{$this->parent}[] = $item;
                }
            } else {
                foreach ($this->fields as $field=>$options) {
                    $value   = $this->getValue($field, $options, $parameters);
                    if (isset ($options['attributes'])) {
                        foreach ($options['attributes'] as $name=>$type) {
                            if (null !== $value) {
                                if ($name === 'type') {
                                    if ($type === 'array') {
                                        if (is_string($value) ||
                                                        is_numeric($value)) {
                                            $value = (array) $value;
                                        } else {
                                            $value = null;
                                        }
                                    } else {
                                        settype($value, $type);
                                    }
                                }
                            }
                        }
                    }
                    if (null !== $value) {
                        if (is_string($value)) {
                            $value = mb_encode_numericentity(
                                $value,
                                [0x80, 0xffff, 0, 0xffff],
                                'utf-8'
                            );
                        }
                        !empty($options['sibling']) ?
                            $object->$field = $value :
                            $parent->$field = $value;
                    }
                }
            }
            $parameters =  json_encode($object);
            $parameters = mb_decode_numericentity(
                $parameters,
                [0x80, 0xffff, 0, 0xffff],
                'utf-8'
            );
        } else {
            $parameters = '{}';
        }

        return $parameters;
    }
}
