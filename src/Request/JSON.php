<?php

namespace TeamWorkPm\Request;

class JSON extends Model
{
    protected function parseParameters(array $parameters): ?string
    {
        $object = new \stdClass();

        if ($this->useFields) {
            $parent = (string) $this->parent;
            $object->$parent = new \stdClass();
            $parent = $object->$parent;
            $noUpdate = $this->method !== 'PUT';
            foreach ($this->fields as $field => $options) {
                $value = $this->getValue($field, $options, $parameters);
                if ($value !== null && $noUpdate &&
                    ($options['on_update'] ?? false) !== false
                ) {
                    continue;
                }
                if (is_string($value)) {
                    $value = $this->decodeNumericEntities($value);
                }
                if ($value !== null) {
                    !empty($options['sibling'])
                        ? $object->$field = $value
                        : $parent->$field = $value;
                }
            }
            $parent = (string) $this->parent;
            if (!count((array) $object->$parent)) {
                unset($object->$parent);
            }
        } else {
            foreach ($parameters as $key => $value) {
                $object->$key = $value;
            }
        }

        $parameters = json_encode($object);
        $parameters = $this->decodeNumericEntities($parameters);

        return $parameters;
    }


    private function decodeNumericEntities(string $text): string
    {
        return mb_decode_numericentity($text, [0x80, 0xffff, 0, 0xffff], 'UTF-8');
    }
}
