<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Validation;

use Northwestern\SysDev\DynamicForms\Components\ComponentInterface;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Textfield;
use Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssueType;
use Northwestern\SysDev\DynamicForms\JSONLogicInitHelper;
use Northwestern\SysDev\DynamicForms\Validation\JSONValidation;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

#[\PHPUnit\Framework\Attributes\CoversClass(\Northwestern\SysDev\DynamicForms\Validation\JSONValidation::class)]
final class JSONValidationTest extends TestCase
{
    private const DATE_EXPECT_FAIL = true;
    private const DATE_EXPECT_PASS = false;

    #[DataProvider('checkDefinitionDataProvider')]
    public function testCheckDefinition(
        array $rule,
        bool $expectsIssue,
        DefinitionIssueType $expectedType = DefinitionIssueType::JsonValidationMissingTrue,
    ): void {
        $validation = new JSONValidation($rule);
        $checked = $validation->checkDefinition($this->getComponent());

        $this->assertSame(! $expectsIssue, $checked->isClean());
        $this->assertCount($expectsIssue ? 1 : 0, $checked->all());

        if ($expectsIssue) {
            $issue = $checked->all()[0];
            $this->assertSame('test', $issue->componentKey);
            $this->assertSame('Test', $issue->componentLabel());
            $this->assertSame('validate.json.if', $issue->jsonPath);
            $this->assertSame($expectedType, $issue->type);
            $this->assertSame($expectedType->severity(), $issue->severity());
            $this->assertNull($issue->details);
        }
    }

    public static function checkDefinitionDataProvider(): array
    {
        $condition = ['!==' => [['var' => 'data.class'], 'junior']];
        $message = 'Only junior students are eligible for this grant!';

        return [
            'true in first output' => [['if' => [$condition, true, $message]], false],
            'true in second output' => [['if' => [$condition, $message, true]], false],
            'both outputs true' => [['if' => [$condition, true, true]], false],
            'original class rule' => [['if' => [$condition, $message, false]], true],
            'true only in condition' => [['if' => [true, $message, false]], true],
            'truthy string is not true' => [['if' => [$condition, 'true', $message]], true],
            'truthy number is not true' => [['if' => [$condition, 1, $message]], true],
            'null output' => [['if' => [$condition, null, $message]], true],
            'nested if that can return true' => [
                ['if' => [$condition, ['if' => [$condition, true, $message]], $message]],
                false,
            ],
            'nested if that only returns messages' => [
                ['if' => [$condition, ['if' => [$condition, $message, 'Another message']], $message]],
                true,
            ],
            'comparison output can return true' => [['if' => [$condition, ['==' => [1, 1]], $message]], false],
            'missing if' => [[], true, DefinitionIssueType::JsonValidationInvalidStructure],
            'null if' => [['if' => null], true, DefinitionIssueType::JsonValidationInvalidStructure],
            'non-array if' => [['if' => 'invalid'], true, DefinitionIssueType::JsonValidationInvalidStructure],
            'too few arguments' => [
                ['if' => [$condition, $message]],
                true,
                DefinitionIssueType::JsonValidationInvalidStructure,
            ],
            'too many arguments' => [
                ['if' => [$condition, $message, false, true]],
                true,
                DefinitionIssueType::JsonValidationInvalidStructure,
            ],
        ];
    }

    public function testCheckDefinitionDoesNotChangeValidation(): void
    {
        $component = $this->getComponent();
        $validation = new JSONValidation([
            'if' => [
                ['===' => [['var' => 'data.class'], 'junior']],
                true,
                'Only juniors are eligible.',
            ],
        ]);

        $before = $validation($component, ['class' => 'senior'])->toArray();
        $validation->checkDefinition($component);

        $this->assertSame($before, $validation($component, ['class' => 'senior'])->toArray());
        $this->assertTrue($validation($component, ['class' => 'junior'])->isEmpty());
    }

    /**
     * @param  array|class-string  $expected
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
            ...self::dataFuzzerProvider(),
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

    public static function dataFuzzerProvider(): array
    {
        $rule = json_decode('{
            "json": {
                "if": [
                    {
                        "<=": [
                            { "var": "startsAt" },
                            { "var": "endsAt" }
                        ]
                    },
                    true,
                    "The absence end date cannot be earlier than the absence start date."
                ]
            }
        }', true);

        return collect([
            // startsAt, endsAt, expectedToFail (bool)
            'should handle dates like the client-side code, fail with custom error message' => [
                '2024-10-05T05:00:00.000000Z',
                '2024-10-01T05:00:00.000000Z',
                self::DATE_EXPECT_FAIL,
            ],
            'should handle dates like the client-side code, pass' => [
                '2024-10-01T05:00:00.000000Z',
                '2024-10-05T05:00:00.000000Z',
                self::DATE_EXPECT_PASS,
            ],
            ...collect(range(1, 12))->mapWithKeys(function (int $month) {
                $month = str_pad($month, '1', '0', STR_PAD_LEFT);

                return [
                    "should handle dates like the client-side code, pass with static low-end date vs 2024-{$month}-XX" => [
                        '2024-01-01T05:00:00.000000Z',
                        "2024-{$month}-05T05:00:00.000000Z",
                        self::DATE_EXPECT_PASS,
                    ],
                    "should handle dates like the client-side code, fail with static high-end date vs 2024-{$month}-XX" => [
                        "2024-{$month}-02T05:00:00.000000Z",
                        '2024-01-01T05:00:00.000000Z',
                        self::DATE_EXPECT_FAIL,
                    ],
                ];
            })->all(),
            'should handle dates like the client-side code, pass with high month' => [
                '2024-01-01T05:00:00.000000Z',
                '2024-10-05T05:00:00.000000Z',
                self::DATE_EXPECT_PASS,
            ],
        ])->map(function (array $data) use ($rule): array {
            [$startsAt, $endsAt, $expectedToFail] = $data;

            return [
                'jsonValidation' => $rule,
                'submissionValues' => ['startsAt' => $startsAt, 'endsAt' => $endsAt],
                'expected' => $expectedToFail
                    ? ['test' => ['The absence end date cannot be earlier than the absence start date.']]
                    : [],
            ];
        })->all();
    }

    #[DataProvider('fallbackMessageProvider')]
    public function testFallbackMessageForFalsyResult(?string $label, ?string $errorLabel, string $expected): void
    {
        $component = $this->getComponent(
            validations: ['json' => ['if' => [true, false, 'Unused']]],
            label: $label,
            errorLabel: $errorLabel,
        );

        $result = $component->advancedValidations()($component, []);

        $this->assertSame(['test' => [$expected]], $result->toArray());
    }

    public static function fallbackMessageProvider(): array
    {
        // label, errorLabel, expected message
        return [
            'uses label' => ['Test', null, 'Test was not valid.'],
            'prefers error label' => ['Test', 'Test Field', 'Test Field was not valid.'],
            'falls back to key for null label' => [null, null, 'test was not valid.'],
            'falls back to key for empty label' => ['', null, 'test was not valid.'],
            'falls back to label for empty error label' => ['Test', '', 'Test was not valid.'],
        ];
    }

    /**
     * Adapted from a real-world rule for a leave-of-absence form's "End of Leave Date" component.
     *
     * Neither output of the top-level "if" is a literal true, but both are nested "if"s that can return true.
     */
    private static function nestedIfOutputsRule(): array
    {
        return json_decode(
            file_get_contents(__DIR__.'/../Fixtures/leave_dates_nested_if_validation.json'),
            true,
            flags: JSON_THROW_ON_ERROR,
        );
    }

    public function testCheckDefinitionWithNestedIfOutputsIsClean(): void
    {
        $validation = new JSONValidation(self::nestedIfOutputsRule());

        $checked = $validation->checkDefinition($this->getComponent());

        $this->assertSame([], $checked->all(), 'Nested "if" outputs can return true, so they should not be flagged.');
    }

    #[DataProvider('nestedIfOutputsProvider')]
    public function testInvokeWithNestedIfOutputs(?string $startOfLeaveDate, ?string $endOfLeaveDate, ?string $expectedError): void
    {
        new JSONLogicInitHelper;

        $component = $this->getComponent(validations: ['json' => self::nestedIfOutputsRule()]);

        $result = $component->advancedValidations()($component, [
            'startOfLeaveDate' => $startOfLeaveDate,
            'endOfLeaveDate' => $endOfLeaveDate,
        ]);

        $this->assertSame($expectedError === null ? [] : ['test' => [$expectedError]], $result->toArray());
    }

    public static function nestedIfOutputsProvider(): array
    {
        $missingStart = 'Enter a start date before entering an end date.';
        $tooEarly = 'The absence end date cannot be earlier than the absence start date.';
        $tooLong = 'The leave period cannot exceed one year.';

        // startOfLeaveDate, endOfLeaveDate, expected error
        return [
            'both dates missing' => [null, null, null],
            'start date missing' => [null, '2024-10-05T05:00:00.000000Z', $missingStart],
            'end date missing' => ['2024-10-05T05:00:00.000000Z', null, null],
            'end before start' => ['2024-10-05T05:00:00.000000Z', '2024-10-01T05:00:00.000000Z', $tooEarly],
            'end before start, previous year' => ['2025-01-05T06:00:00.000000Z', '2024-12-20T06:00:00.000000Z', $tooEarly],
            'same day' => ['2024-10-05T05:00:00.000000Z', '2024-10-05T05:00:00.000000Z', null],
            'a few days' => ['2024-10-01T05:00:00.000000Z', '2024-10-05T05:00:00.000000Z', null],
            'crosses into next year' => ['2024-11-01T05:00:00.000000Z', '2025-03-01T06:00:00.000000Z', null],
            'one day short of a year' => ['2024-11-01T05:00:00.000000Z', '2025-10-31T05:00:00.000000Z', null],
            'exactly one year' => ['2024-11-01T05:00:00.000000Z', '2025-11-01T05:00:00.000000Z', null],
            'one year and a day' => ['2024-11-01T05:00:00.000000Z', '2025-11-02T05:00:00.000000Z', $tooLong],
            'two years' => ['2024-11-01T05:00:00.000000Z', '2026-11-01T05:00:00.000000Z', $tooLong],
            'single-digit month' => ['2024-03-15T05:00:00.000000Z', '2025-03-16T05:00:00.000000Z', $tooLong],
        ];
    }

    private function getComponent(
        array $validations = [],
        ?string $label = 'Test',
        ?string $errorLabel = null,
    ): ComponentInterface {
        /** @var ComponentInterface $component */
        return new Textfield(
            key: 'test',
            label: $label,
            errorLabel: $errorLabel,
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
