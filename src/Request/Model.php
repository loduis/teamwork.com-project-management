<?php

namespace TeamWorkPm\Request;

use TeamWorkPm\Exception;
use TeamWorkPm\Helper\Str;

abstract class Model
{
    protected bool $useFields = true;

    protected ?string $method = null;

    protected ?string $action = null;

    protected ?string $parent = null;

    protected array $fields = [];

    /**
     * @param string $parent
     * @return $this
     */
    public function setParent(string $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @param string $action
     * @return $this
     */
    public function setAction(string $action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param string $field
     * @param bool|array $options
     * @param array $parameters
     *
     * @return mixed|null
     * @throws Exception
     */
    protected function getValue(string &$field, bool|array &$options, array $parameters)
    {
        if (!is_array($options)) {
            $options = ['required' => $options, 'attributes' => []];
        }
        $type = $options['type'] ?? 'string';
        $transform = $options['transform'] ?? null;
        $originalField = $field;
        $value = $parameters[$field] ?? null;
        $accept = $options['accept'] ?? null;
        if ($transform !== null) {
            $fieldTransform = $transform;
            $checkValue = !array_key_exists($field, $parameters);
            $this->transformField($field, $fieldTransform);
            if ($checkValue) {
                $value = $parameters[$field] ?? null;
            }
        }
        if ($type !== null && $value !== null && is_scalar($value)) {
            if ($type === 'array') {
                if (is_string($value) || is_numeric($value)) {
                    $value = (array)$value;
                }
            } elseif (!str_contains($type, '<') && !in_array($type, ['email', 'url', 'country'])) {
                settype($value, $type);
            }
        }

        $this->validate($originalField, $value, $options);

        if ($type === 'object') {
            if ($accept && preg_match('/^<(\w+)>$/', $accept, $matches) && (is_arr_obj($value)) && array_is_list((array) $value)) {
                $wrapField = $matches[1];
                if (isset($options[$wrapField])) {
                    $value = [$wrapField => $value];
                    if (isset($options[$wrapField]['fields'])) {
                        $fieldOpts = $options[$wrapField];
                        $fieldValue = $value[$wrapField];
                        $fieldValue = $this->getValue($wrapField, $fieldOpts, $value);
                        $value[$wrapField] = $fieldValue;
                    }
                }
            } elseif (is_object($value) || (
                is_array($value) && ($value === [] || !array_is_list($value)))
            ) {
                $res = [];
                foreach ($options as $key => $opts) {
                    if (is_scalar($opts)) {
                        continue;
                    }
                    $val = $this->getValue($key, $opts, $value);
                    if ($val !== null) {
                        $res[$key] = $val;
                    }
                }
                $value = $res;
            }
        }
        if ($type === 'array<object>' &&
            is_array($value) &&
            isset($options['fields'])
        ) {
            if ($accept &&
                preg_match('/<(.+?),(.+?)>/', $accept, $matches) &&
                !array_is_list((array) $value)
            ) {
                $idField = $matches[1];
                $valueField = $matches[2];
                $processedValue = [];
                $indexByKey = $idField === '$key';
                if ($indexByKey) {
                    foreach ($value as $key => $val) {
                        $processedValue[$key] = [
                            $valueField => is_scalar($val) ? $val : ((array)$val)[$valueField]
                        ];
                    }
                    return $processedValue;
                }

                foreach ($value as $key => $val) {
                    $processedValue[] = [
                        $idField => $key,
                        $valueField => $val
                    ];
                }
                $value = $processedValue;
            }

            $_options = $options['fields'];

            foreach ($value as $i => $entry) {
                $res = [];
                foreach ($_options as $key => $opts) {
                    $val = $this->getValue($key, $opts, $entry);
                    if ($val !== null) {
                        $res[$key] = $val;
                    }
                }
                /*
                foreach ($entry as $key => $val) {
                    $val = $this->getValue($key, $opts[$key], $entry);
                    $res[$key] = $val;
                }
                */

                $value[$i] = $res;
            }
        }

        return $value;
    }

    protected function validate(string $field, mixed $value, array $options): void
    {
        $isNull = $value === null || $value === '';
        if ($this->method === 'POST' && (
            $options['required'] ?? false
            ) !== false && $isNull
        ) {
            throw new Exception('Required field ' . $field);
        }
        // checking fields that must meet certain values
        if (!$isNull   && (
                (
                    isset($options['validate']) &&
                    is_array($options['validate']) &&
                    !in_array($value, $options['validate'])
                ) || (
                    ($options['type'] ?? 'string') === 'email' &&
                    !filter_var($value, FILTER_VALIDATE_EMAIL)
                ) || (
                    ($options['type'] ?? 'string') === 'url' &&
                    !filter_var($value, FILTER_VALIDATE_URL)
                ) || (
                    ($options['type'] ?? 'string') === 'country' &&
                    mb_strlen($value) !== 2
                )
        )) {
            throw new Exception(
                'Invalid value for field ' .
                $field
            );
        }
    }

    public function getParent(): string
    {
        return (string) $this->parent;
    }

    /**
     * Return params
     *
     * @param string $method
     * @param array|object|null $parameters
     * @return string|null
     */
    public function getParameters(string $method, object|array|null $parameters): ?string
    {
        $result = null;

        if ($parameters === null) {
            return null;
        }

        $this->method = $method;
        if ($isArrayObject = is_arr_obj($parameters)) {
            $parameters = arr_obj($parameters)->toArray();
        }
        if ($method === 'GET') {
            if ($isArrayObject) {
                $result = http_build_query($parameters);
            }
        } elseif ($isArrayObject) {
            /** @psalm-suppress PossiblyInvalidArgument */
            $result = $this->parseParameters($parameters);
            $this->useFields = true;
        }

        return $result;
    }

    private function transformField(&$field, $fieldTransform)
    {
        if ($fieldTransform !== null) {
            $field = in_array($fieldTransform, ['camel', 'dash']) ?
                Str::{$fieldTransform}($field) :
                $fieldTransform;
        }
    }

    public function notUseFields()
    {
        $this->useFields = false;
    }

    /**
     * Return parameters for post and put request
     *
     * @param array $parameters
     * @return string
     */
    abstract protected function parseParameters(array $parameters): ?string;
}
