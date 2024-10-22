<?php

namespace {

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
     * @return stdClass|ArrayObject
     */
    function arr_obj(iterable $data) {
        return new \ArrayObject($data, \ArrayObject::ARRAY_AS_PROPS);
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
}