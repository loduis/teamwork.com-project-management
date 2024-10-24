<?php

namespace {

    use TeamWorkPm\ArrayObject;

    final class Tpm extends \TeamWorkPm\Factory
    {
        public static function auth(...$args)
        {
            if (count($args) === 3) {
                $format = $args[2];
                \TeamWorkPm\Rest\Client::setFormat($format);
                unset($args[2]);
            }
            \TeamWorkPm\Auth::set(...$args);
        }
    }

    if (!function_exists('array_is_list')) {
        function array_is_list(array $array)
        {
            $keys = array_keys($array);

            return array_keys($keys) === $keys;
        }
    }

    /**
     * @param iterable $entries
     *
     * @return ArrayObject|\stdClass
     */
    function arr_obj(iterable $entries = [], int $options = ArrayObject::ARRAY_AS_PROPS | ArrayObject::STD_PROP_LIST): ArrayObject
    {
        foreach ($entries as $key => $entry) {
            if (is_array($entry)) {
                $entries[$key] = arr_obj($entry, $options);
            }
        }

        return new ArrayObject($entries, $options);
    }
}


namespace TeamWorkPm {
    if (!function_exists(__NAMESPACE__ . '\array_reduce')) {
        function array_reduce(array $array, callable $callback, $initial = null)
        {
            $acc = $initial;
            foreach ($array as $key => $val) {
                $acc = $callback($acc, $val, $key);
            }

            return $acc;
        }
    }

    class ArrayObject extends \ArrayObject
    {
        public function toArray(): array
        {
            $array = [];
            foreach ($this->getArrayCopy() as $key => $value) {
                $array[$key] = $value instanceof static ? $value->toArray() : $value;
            }

            return $array;
        }

        public function reduce(callable $callback, $initial = [])
        {
            $accumulator = $initial;
            foreach ($this as $key => $value) {
                $accumulator = $callback($accumulator, $value, $key);
            }

            return $accumulator;
        }
    }
}
