<?php

namespace TeamWorkPm\Custom;

use TeamWorkPm\Exception;
use TeamWorkPm\Model;

class Field extends Model
{
    protected ?string $action = 'projects/api/v3/customfields';

    protected ?string $parent = 'customfield';

    protected function init()
    {
        $this->fields = [
            'name' => [
                'type' => 'string',
                'required' => true
            ],
            'entity' => [
                'type' => 'string',
                'required' => true,
                'validate' => [
                    'project',
                    'task'
                ]
            ],
            'type' => [
                'type' => 'string',
                'required' => true,
                'validate' => [
                    'text-short',
                    'number-integer',
                    'date',
                    'url',
                    'checkbox',
                    'dropdown',
                    'status'
                ]
            ],
            'description' => [
                'type' => 'string'
            ],
            'formula' => [
                'type' => 'string'
            ],
            'project_id' => [
                'type' => 'integer',
                'transform' => 'camel'
            ],
            'required' => [
                'type' => 'boolean',
            ],
            'is_private' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'options' => [
                'type' => 'array',
                'transform' => [null, function ($value) {
                    $choices = array_reduce($value, function ($acc, $entry) {
                        $entry  = arr_obj($entry);
                        if (empty($entry->color) || empty($entry->value)) {
                            throw new Exception('Invalid value for field options');
                        }
                        $acc[] = [
                            'color' => $entry->color,
                            'value' => $entry->value
                        ];
                        return $acc;
                    }, []);

                    return $choices ? compact('choices') : null;
                }]
            ]
        ];
    }

    public function all(array $params = [])
    {
        return $this->rest->get("$this->action", $params);
    }
}
