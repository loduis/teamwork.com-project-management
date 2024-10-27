<?php

namespace {

    use TeamWorkPm\ArrayObject;

    final class Tpm extends \TeamWorkPm\Factory
    {
        public static function auth(string ...$args): void
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
        function array_is_list(array $array): bool
        {
            $keys = array_keys($array);

            return array_keys($keys) === $keys;
        }
    }

    /**
     * @param array|object $entries
     *
     * @return ArrayObject
     */
    function arr_obj(array|object $entries = [], int $options = ArrayObject::ARRAY_AS_PROPS | ArrayObject::STD_PROP_LIST): ArrayObject
    {
        if ($entries instanceof ArrayObject) {
            return $entries;
        }
        if (is_object($entries)) {
            $entries = (array) $entries;
        }
        /**
         * @var array|object
         */
        foreach ($entries as &$entry) {
            if (is_arr_obj($entry)) {
                $entry = arr_obj($entry, $options);
            }
        }
        unset($entry);

        return new ArrayObject($entries, $options);
    }

    function is_arr_obj(mixed $value): bool
    {
        return is_array($value) || is_object($value);
    }
}


namespace TeamWorkPm {
    if (!function_exists(__NAMESPACE__ . '\array_reduce')) {
        function array_reduce(object|iterable $array, callable $callback, mixed $initial = null): mixed
        {
            /**
             * @var mixed
             */
            $acc = $initial;
            /**
             * @var mixed
             */
            foreach ($array as $key => $val) {
                /**
                 * @var mixed
                 */
                $acc = $callback($acc, $val, $key);
            }

            return $acc;
        }
    }

    /**
     * @template TKey of array-key
     * @template TValue
     * @extends \ArrayObject<TKey,TValue>
     */
    class ArrayObject extends \ArrayObject
    {
        public function toArray(): array
        {
            $array = [];
            foreach ($this->getArrayCopy() as $key => $value) {
                $array[$key] = $value instanceof self ? $value->toArray() : $value;
            }

            return $array;
        }

        public function reduce(callable $callback, mixed $initial = []): mixed
        {
            /**
             * @var mixed
             */
            $accumulator = $initial;
            foreach ($this as $key => $value) {
                /**
                 * @var mixed
                 */
                $accumulator = $callback($accumulator, $value, $key);
            }

            return $accumulator;
        }

        public function __debugInfo(): array
        {
            return $this->toArray();
        }

        /**
         *
         * @param TKey $key
         * @return mixed
         */
        public function offsetGet(mixed $key): mixed
        {
            return $this->offsetExists($key) ?
                parent::offsetGet($key) : null;
        }

        /**
         *
         * @param TKey $key
         * @return void
         */
        public function offsetUnset(mixed $key): void
        {
            if ($this->offsetExists($key)) {
                parent::offsetUnset($key);
            }
        }

        /**
         *
         * @param TKey $key
         * @return mixed
         */
        public function pull(mixed $key): mixed
        {
            if (!$this->offsetExists($key)) {
                return null;
            }
            /**
             * @var mixed
             */
            $value = $this->offsetGet($key);
            $this->offsetUnset($key);

            return $value;
        }

        public function has(): bool
        {
            return count($this) > 0;
        }
    }
}
