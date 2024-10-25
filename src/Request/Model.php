<?php

namespace TeamWorkPm\Request;

use TeamWorkPm\Exception;
use TeamWorkPm\Helper\Str;

abstract class Model
{
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
     * @throws \TeamWorkPm\Exception
     */
    protected function getValue(string &$field, bool|array &$options, array $parameters)
    {
        $transform = $options['transform'] ?? null;
        $originalField = $field;
        $value = $parameters[$field] ?? null;
        if ($hasTransform = ($transform !== null)) {
            $fieldTransform = $transform;
            $valueTransform = null;
            if (is_array($transform)) {
                [$fieldTransform, $valueTransform] = $transform;
            }
            $checkValue = !array_key_exists($field, $parameters);
            if ($fieldTransform !== null) {
                $field = in_array($fieldTransform, ['camel', 'dash']) ?
                    Str::{$fieldTransform}($field) :
                    $fieldTransform;
            }
            if ($checkValue) {
                $value = $parameters[$field] ?? null;
            }
            if ($valueTransform !== null && $value !== null) {
                $value = $valueTransform($value);
            }
        }
        if (!is_array($options)) {
            $options = ['required' => $options, 'attributes' => []];
        }

        $this->validate($originalField, $value, $options);

        if (!$hasTransform) {
            $this->transform($field);
        }

        return $value;
    }

    protected function validate(string $field, mixed $value, array $options): void
    {
        $isNull = $value === null || $value === '';
        if ($this->method === 'POST' && ($options['required'] ?? false) !== false && $isNull) {
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
                )
        )) {
            throw new Exception(
                'Invalid value for field ' .
                $field
            );
        }
    }

    protected function transform(string &$field): void
    {
        static $camelize = [
            'pending_file_attachments' => true,
            'date_format' => true,
            'send_welcome_email' => true,
            'receive_daily_reports' => true,
            'welcome_email_message' => true,
            'auto_give_project_access' => true,
            'open_id' => true,
            'user_language' => true,
            'pending_file_ref' => true,
            'new_company' => true,
            'industry_cat_id' => true,
            'tag_ids' => true,
            'logo_pending_file_ref' => true,
            'remove_logo' => true,
            'private_notes' => true
        ],
        $yes_no_boolean = [
            'welcome_email_message',
            'send_welcome_email',
            'receive_daily_reports',
            'notes',
            'auto_give_project_access',
        ],
        $preserve = [
            'address_one' => true,
            'address_two' => true,
            'email_one' => true,
            'email_two' => true,
            'email_three' => true
        ];

        // @todo Note that the people at team work do not constantly maintain the name-other format.
        if (isset($camelize[$field])) {
            if ($field === 'open_id') {
                $field = 'openID';
            } else {
                $field = Str::camel($field);
            }
        } elseif (!isset($preserve[$field])) {
            if ($field === 'company_id') {
                if ($this->action === 'projects') {
                    $field = Str::camel($field);
                } elseif ($this->action === 'people') {
                    $field = Str::dash($field);
                }
            } else {
                $field = Str::dash($field);
            }
        }
    }

    protected function actionInclude(mixed $value): bool
    {
        return str_contains((string) $this->action, (string)$value);
    }

    public function getParent(): string
    {
        return (string)$this->parent . ($this->actionInclude('/reorder') ? 's' : '');
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
        if ($isOA = is_arr_obj($parameters)) {
            $parameters = arr_obj($parameters)->toArray();
        }
        if ($method === 'GET') {
            if ($isOA) {
                $result = http_build_query($parameters);
            }
        } elseif ($isOA) {
            /** @psalm-suppress PossiblyInvalidArgument */
            $result = $this->parseParameters($parameters);
        }

        return $result;
    }

    /**
     * Return parameters for post and put request
     *
     * @param array $parameters
     * @return string
     */
    abstract protected function parseParameters(array $parameters): ?string;
}
