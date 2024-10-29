<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Validation;

use Northwestern\SysDev\DynamicForms\Components\ComponentInterface;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Textfield;
use Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError;
use Northwestern\SysDev\DynamicForms\JSONLogicInitHelper;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Validation\JSONValidation
 */
final class JSONValidationTest extends TestCase
{
    /**
     * @param  array|class-string  $expected
     *
     * @covers ::isValidCustomValidation
     */
    #[DataProvider('invokeDataProvider')]
    public function testInvoke(array $jsonValidation, array $submissionValues, array|string $expected): void
    {
        new JSONLogicInitHelper;

        $component = $this->getComponent(validations: $jsonValidation);

        if (is_string($expected) && class_exists($expected)) {
            $this->expectException($expected);
        }

        $result = $component->advancedValidations()($component, $submissionValues);

        if (empty($expected)) {
            $this->assertTrue($result->isEmpty(), 'Expected no validation errors, but some were found.');
        } else {
            foreach ($expected as $field => $messages) {
                $this->assertTrue(
                    $result->has($field),
                    "Expected validation error for field '{$field}'."
                );
                foreach ($messages as $message) {
                    $this->assertContains(
                        $message,
                        $result->get($field),
                        "Expected message '{$message}' for field '{$field}'."
                    );
                }
            }

            $this->assertCount(count($expected), $result->all());
        }
    }

    public static function invokeDataProvider(): array
    {
        return [
            'should throw exception when "if" parameter is missing' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "===": [
                            {
                                "var": "foo"
                            },
                            "bar"
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => 'foo'],
                'expected' => InvalidDefinitionError::class,
            ],
            'should throw exception when "if" parameter has less than three arguments' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            { "===": [ { "var": "foo" }, "bar" ] },
                            true
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => 'baz'],
                'expected' => InvalidDefinitionError::class,
            ],
            'should throw exception when "if" parameter has more than three arguments' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            { "===": [ { "var": "foo" }, "bar" ] },
                            true,
                            "Values must be equal",
                            "Extra argument"
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => 'baz'],
                'expected' => InvalidDefinitionError::class,
            ],
            'should throw exception when "if" condition is not an array' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": "not an array"
                    }
                }', true),
                'submissionValues' => ['foo' => 'baz'],
                'expected' => InvalidDefinitionError::class,
            ],
            'should fail when values are not equal with custom message' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            { "===": [ { "var": "foo" }, "bar" ] },
                            true,
                            "Values must be equal"
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => 'foo'],
                'expected' => [
                    'test' => [
                        'Values must be equal',
                    ],
                ],
            ],
            'should pass when values are equal' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            { "===": [ { "var": "foo" }, "bar" ] },
                            true,
                            "Values must be equal"
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => 'bar'],
                'expected' => [],
            ],
            'should fail when values are equal with custom message' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            { "!==": [ { "var": "foo" }, "bar" ] },
                            true,
                            "Values must not be equal"
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => 'bar'],
                'expected' => [
                    'test' => [
                        'Values must not be equal',
                    ],
                ],
            ],
            'should pass when values are not equal' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            { "!==": [ { "var": "foo" }, "bar" ] },
                            true,
                            "Values must not be equal"
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => 'baz'],
                'expected' => [],
            ],
            'should fail when value is not in the list with custom message' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            { "in": [ { "var": "foo" }, ["bar", "baz"] ] },
                            true,
                            "Value must be either bar or baz"
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => 'qux'],
                'expected' => [
                    'test' => [
                        'Value must be either bar or baz',
                    ],
                ],
            ],
            'should pass when value is in the list' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            { "in": [ { "var": "foo" }, ["bar", "baz"] ] },
                            true,
                            "Value must be either bar or baz"
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => 'bar'],
                'expected' => [],
            ],
            'should fail when value is not greater than the threshold with custom message' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            { ">": [ { "var": "foo" }, 10 ] },
                            true,
                            "Value must be greater than 10"
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => 5],
                'expected' => [
                    'test' => [
                        'Value must be greater than 10',
                    ],
                ],
            ],
            'should pass when value is greater than the threshold' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            { ">": [ { "var": "foo" }, 10 ] },
                            true,
                            "Value must be greater than 10"
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => 15],
                'expected' => [],
            ],
            'should fail when value is not less than the threshold with custom message' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            { "<": [ { "var": "foo" }, 20 ] },
                            true,
                            "Value must be less than 20"
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => 25],
                'expected' => [
                    'test' => [
                        'Value must be less than 20',
                    ],
                ],
            ],
            'should pass when value is less than the threshold' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            { "<": [ { "var": "foo" }, 20 ] },
                            true,
                            "Value must be less than 20"
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => 15],
                'expected' => [],
            ],
            'should fail multiple fields with custom messages' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            { "===": [ { "var": "foo" }, "bar" ] },
                            true,
                            "Foo must be equal to bar"
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => 'baz'],
                'expected' => [
                    'test' => [
                        'Foo must be equal to bar',
                    ],
                ],
            ],
            'should handle nested conditions and fail with custom message' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            {
                                "and": [
                                    { "===": [ { "var": "foo" }, "bar" ] },
                                    { ">": [ { "var": "baz" }, 10 ] }
                                ]
                            },
                            true,
                            "Foo must be bar and baz must be greater than 10"
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => 'bar', 'baz' => 5],
                'expected' => [
                    'test' => [
                        'Foo must be bar and baz must be greater than 10',
                    ],
                ],
            ],
            'should handle nested conditions and pass when all conditions are met' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            {
                                "and": [
                                    { "===": [ { "var": "foo" }, "bar" ] },
                                    { ">": [ { "var": "baz" }, 10 ] }
                                ]
                            },
                            true,
                            "Foo must be bar and baz must be greater than 10"
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => 'bar', 'baz' => 15],
                'expected' => [],
            ],
            'should fail when submissionValues is empty' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            { "===": [ { "var": "foo" }, "bar" ] },
                            true,
                            "Foo must be bar"
                        ]
                    }
                }', true),
                'submissionValues' => [],
                'expected' => [
                    'test' => [
                        'Foo must be bar',
                    ],
                ],
            ],
            'should fail when submissionValues is null' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            { "===": [ { "var": "foo" }, "bar" ] },
                            true,
                            "Foo must be bar"
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => null],
                'expected' => [
                    'test' => [
                        'Foo must be bar',
                    ],
                ],
            ],
            'should handle non-string values correctly' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            { "===": [ { "var": "foo" }, 100 ] },
                            true,
                            "Foo must be 100"
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => '100'],
                'expected' => [
                    'test' => [
                        'Foo must be 100',
                    ],
                ],
            ],
            'should handle complex logical operators and fail with custom message' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            {
                                "and": [
                                    { "===": [ { "var": "foo" }, "bar" ] },
                                    { "===": [ { "var": "baz" }, "qux" ] }
                                ]
                            },
                            true,
                            "Foo must be bar and Baz must be qux"
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => 'bar', 'baz' => 'not_qux'],
                'expected' => [
                    'test' => [
                        'Foo must be bar and Baz must be qux',
                    ],
                ],
            ],
            'should handle complex logical operators and pass when all conditions are met' => [
                'jsonValidation' => json_decode('{
                    "json": {
                        "if": [
                            {
                                "and": [
                                    { "===": [ { "var": "foo" }, "bar" ] },
                                    { "===": [ { "var": "baz" }, "qux" ] }
                                ]
                            },
                            true,
                            "Foo must be bar and Baz must be qux"
                        ]
                    }
                }', true),
                'submissionValues' => ['foo' => 'bar', 'baz' => 'qux'],
                'expected' => [],
            ],
        ];
    }

    private function getComponent(
        array $validations = [],
    ): ComponentInterface {
        /** @var ComponentInterface $component */
        return new Textfield(
            key: 'test',
            label: 'Test',
            errorLabel: null,
            components: [],
            validations: $validations,
            hasMultipleValues: false,
            conditional: null,
            customConditional: null,
            case: 'mixed',
            calculateValue: null,
            defaultValue: null,
            additional: [],
        );
    }
}
