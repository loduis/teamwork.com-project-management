<?php

namespace TeamWorkPm\Tests\Custom;

use TeamWorkPm\Exception;
use TeamWorkPm\Tests\TestCase;

final class FieldTest extends TestCase
{
    /**
     * @dataProvider provider
     * @test
     */
    public function insertValidateType($data): void
    {
        $data['type'] = 'invalid-type';
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid value for field type');
        $this->postTpm('custom.field')->save($data);
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function insert($data): void
    {
        $data['description'] = 'A custom field description';

        $this->assertEquals(10, $this->postTpm('custom.field', function ($headers) {
            $customField = $headers['X-Params'];
            $this->assertObjectHasProperty('name', $customField);
            $this->assertObjectHasProperty('entity', $customField);
            $this->assertObjectHasProperty('type', $customField);
            $this->assertObjectNotHasProperty('invalidField', $customField);
        })->save($data));
    }

    /**
     * @depends      insert
     * @dataProvider provider
     * @test
     */
    public function update($data): void
    {
        $data['name'] = rand_string($data['name']);
        $data['description'] = 'Updated description';
        $data['id'] = 10;

        $this->assertTrue($this->putTpm('custom.field', function ($headers) {
            $customField = $headers['X-Params'];
            $this->assertEquals('Updated description', $customField->description);
        })->save($data));
    }

    /**
     * @depends insert
     * @test
     */
    public function get(): void
    {
        try {
            $this->getTpm('custom.field')->get(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }

        $this->assertEquals(
            'Test Field',
            $this->getTpm('custom.field')->get(62427)->name
        );
    }

    /**
     * @dataProvider validDataProvider
     * @test
     */
    public function insertValidField($data): void
    {
        $this->assertEquals(10, $this->postTpm('custom.field', function ($headers) {
            $customField = $headers['X-Params'];
            $this->assertObjectHasProperty('name', $customField);
            $this->assertObjectHasProperty('entity', $customField);
            $this->assertObjectHasProperty('type', $customField);
            $this->assertObjectHasProperty('description', $customField);
            $this->assertObjectHasProperty('options', $customField);
            $this->assertNotEmpty($customField->options->choices);
        })->save($data));
    }

    /**
     * @dataProvider invalidDataProvider
     * @test
     */
    public function insertInvalidField($data, $expectedMessage): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($expectedMessage);
        $this->postTpm('custom.field')->save($data);
    }

    /**
     * @test
     */
    public function getAll(): void
    {
        $this->assertGreaterThan(0, count($this->getTpm('custom.field')->all()));
    }

    public function provider()
    {
        return [
            [
                [
                    'name' => 'Test Field',
                    'entity' => 'project',
                    'type' => 'text-short',
                ],
            ],
        ];
    }

    /**
     * Proveedor de datos válidos.
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            [
                [
                    'name' => 'Valid Custom Field',
                    'entity' => 'project',
                    'type' => 'text-short',
                    'description' => 'A valid custom field for testing',
                    'options' => [
                        ['color' => 'red', 'value' => 'High'],
                        ['color' => 'green', 'value' => 'Low']
                    ],
                ]
            ],
            [
                [
                    'name' => 'Dropdown Field',
                    'entity' => 'task',
                    'type' => 'dropdown',
                    'description' => 'A dropdown custom field',
                    'options' => [
                        ['color' => 'blue', 'value' => 'Option 1'],
                        ['color' => 'yellow', 'value' => 'Option 2']
                    ],
                ]
            ]
        ];
    }

    /**
     * Proveedor de datos inválidos.
     * @return array
     */
    public function invalidDataProvider(): array
    {
        return [
            // Campo requerido faltante: 'name'
            [
                [
                    'entity' => 'project',
                    'type' => 'text-short',
                    'description' => 'A custom field without a name',
                ],
                'Required field name'
            ],
            // Campo requerido faltante: 'entity'
            [
                [
                    'name' => 'Invalid Field',
                    'type' => 'text-short',
                    'description' => 'Missing entity field',
                ],
                'Required field entity'
            ],
            // Valor no válido para el campo 'type'
            [
                [
                    'name' => 'Invalid Type Field',
                    'entity' => 'project',
                    'type' => 'invalid-type',
                    'description' => 'Invalid type value',
                ],
                'Invalid value for field type'
            ],
            // Campo 'options' con formato incorrecto
            [
                [
                    'name' => 'Invalid Options Field',
                    'entity' => 'project',
                    'type' => 'dropdown',
                    'description' => 'Field with invalid options',
                    'options' => [
                        ['color' => 'blue'],
                        ['value' => 'Missing color']
                    ]
                ],
                'Invalid value for field options'
            ]
        ];
    }
}
