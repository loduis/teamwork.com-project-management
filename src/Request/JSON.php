<?php

namespace TeamWorkPm\Request;

class JSON extends Model
{
    protected function parseParameters(array $parameters): ?string
    {
        $object = new \stdClass();
        $parent = $this->getParent();
        $object->$parent = new \stdClass();
        $parent = $object->$parent;

        if ($this->actionInclude('/reorder')) {
            foreach ($parameters as $id) {
                $item = new \stdClass();
                $item->id = $id;
                $parent->{$this->parent}[] = $item;
            }
        } else {
            $noUpdate = $this->method !== 'PUT';
            foreach ($this->fields as $field => $options) {
                $value = $this->getValue($field, $options, $parameters);
                if ($value !== null && $noUpdate &&
                    ($options['on_update'] ?? false) !== false
                ) {
                    continue;
                }
                if (isset($options['type']) && $value !== null) {
                    $type = $options['type'];
                    if ($type === 'array') {
                        if (is_string($value) || is_numeric($value)) {
                            $value = (array)$value;
                        }
                    } elseif (!in_array($type, ['email'])) {
                        settype($value, $type);
                    }
                }
                if ($value !== null) {
                    if (is_string($value)) {
                        $value = mb_encode_numericentity(
                            $value,
                            [0x80, 0xffff, 0, 0xffff],
                            'utf-8'
                        );
                    }
                    !empty($options['sibling'])
                        ? $object->$field = $value
                        : $parent->$field = $value;
                }
            }
        }
        $parameters = json_encode($object);
        $parameters = mb_decode_numericentity(
            $parameters,
            [0x80, 0xffff, 0, 0xffff],
            'utf-8'
        );

        return $parameters;
    }
}
