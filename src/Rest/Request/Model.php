<?php

declare(strict_types = 1);

namespace TeamWorkPm\Rest\Request;

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
    public function setParent(?string $parent)
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
    public function setFields(array $fields): static
    {
        $this->fields = $fields;
        return $this;
    }

    public function setOpts(array $opts): static
    {
        foreach ($opts as $key => $value) {
            $this->{'set'. $key}($value);
        }

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
        $originalField = $field;
        $value = $parameters[$field] ?? null;
        if (($transform =$options['transform'] ?? null) !== null) {
            $checkValue = !array_key_exists($field, $parameters);
            $this->transformField($field, $transform);
            if ($checkValue) {
                $value = $parameters[$field] ?? null;
            }
        }

        $value = $this->coerceValue($value, $type);

        $this->validate($originalField, $value, $options);

        if ($type === 'object') {
            $value = $this->handleObjectType($value, $options);
        } elseif ($type === 'array<object>' &&
            is_array($value) &&
            isset($options['properties'])
        ) {
            $value = $this->handleArrayOfObjectType($value, $options);
        } elseif ($value !== null && in_array($type, ['string|array<integer>', 'string|array<string>'])) {
            if (is_array($value)) {
                $value = implode(',', $value);
            } else {
                $value = (string) $value;
            }
        }

        return $value;
    }

    private function handleArrayOfObjectType(mixed $value, $options)
    {
        $accept = $options['accept'] ?? null;
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

        $_options = $options['properties'];

        foreach ($value as $i => $entry) {
            $res = [];
            foreach ($_options as $key => $opts) {
                $val = $this->getValue($key, $opts, $entry);
                if ($val !== null) {
                    $res[$key] = $val;
                }
            }
            $value[$i] = $res;
        }

        return $value;
    }

    private function handleObjectType(mixed $value, $options)
    {
        $accept = $options['accept'] ?? null;
        if ($accept && preg_match('/^<(\w+)>$/', $accept, $matches) && (is_arr_obj($value)) && array_is_list((array) $value)) {
            $wrapField = $matches[1];
            if (isset($options[$wrapField])) {
                $value = [$wrapField => $value];
                if (isset($options[$wrapField]['properties'])) {
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

        return $value;
    }

    private function coerceValue($value, string $type)
    {
        if ($value === null) {
            return null;
        }

        if (is_scalar($value)) {
            if ($type === 'array') {
                return (array) $value;
            } elseif (!str_contains($type, '<') && !in_array($type, ['email', 'url', 'country'])) {
                settype($value, $type);
            }
        }

        return $value;
    }

    protected function validate(string $field, mixed $value, array $options): void
    {
        $isNull = $value === null;
        if ($this->method === 'POST' && (
            $options['required'] ?? false
            ) !== false && $isNull
        ) {
            throw new Exception('Required field ' . $field);
        }
        // checking fields that must meet certain values
        if (!$isNull   && (
                (
                    $options['type'] == 'string|array<integer>' &&
                    !$this->validateStringArrayOfInteger($value, $options)
                ) || (
                    $options['type'] == 'string|array<string>' &&
                    !$this->validateStringArrayOfString($value, $options)
                ) ||
                (
                    !str_contains($options['type'], '|') &&
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
     * @return null|string|array
     */
    public function getParameters(string $method, object|array|null $parameters): null|string|array
    {
        $result = null;

        $this->method = $method;

        if ($parameters === null) {
            return null;
        }

        if ($method === 'POST' &&
            is_array($parameters) &&
            isset($parameters['file']) &&
            $parameters['file'] instanceof \CURLFile
        ) {
            $this->useFields = true;
            return $parameters;
        }

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
        $field = in_array($fieldTransform, ['camel', 'dash']) ?
            Str::{$fieldTransform}($field) :
            $fieldTransform;
    }

    private function validateStringArrayOfString(string|array $value): bool
    {
        if (is_string($value)) {
            if (str_contains($value, ',')) {
                $ids = explode(',', $value);
                $ids = array_map(fn($val)=> trim($val), $ids);
                return $this->isArrayOfStrings($ids);
            }
            return true;
        }

        return $this->isArrayOfStrings($value);
    }

    private function validateStringArrayOfInteger(int|string|array $value, array $options): bool
    {
        if (is_string($value)) {
            if (in_array($value, $options['validate'])) {
                return true;
            }
            if (str_contains($value, ',') || ctype_digit($value)) {
                $ids = explode(',', $value);
                $ids = array_map(fn($val)=> trim($val), $ids);
                return $this->isArrayOfIntegers($ids);
            }
            return false;
        }
        if (is_int($value)) {
            return true;
        }

        return $this->isArrayOfIntegers($value);
    }

    private function isArrayOfIntegers($array): bool
    {
        if (!is_array($array)) {
            return false;
        }

        foreach ($array as $value) {
            if (!is_scalar($value) || !ctype_digit((string)$value)) {
                return false;
            }
        }

        return true;
    }

    private function isArrayOfStrings($array): bool
    {
        if (!is_array($array)) {
            return false;
        }

        foreach ($array as $value) {
            if (!is_scalar($value) || !is_string($value)) {
                return false;
            }
        }

        return true;
    }

    public function notUseFields()
    {
        $this->useFields = false;
    }

    public function useFiles()
    {
        return $this->useFields;
    }

    /**
     * Return parameters for post and put request
     *
     * @param array $parameters
     * @return string
     */
    abstract protected function parseParameters(array $parameters): ?string;
}
