<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Conditional;

use Northwestern\SysDev\DynamicForms\Conditional\SimpleConditional;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Conditional\SimpleConditional
 */
final class SimpleConditionalTest extends TestCase
{
    #[DataProvider('invokeDataProvider')]
    public function testInvoke(bool $show, string $when, string $equalTo, array $submissionValues, bool $expected): void
    {
        $condition = new SimpleConditional($show, $when, $equalTo);

        $this->assertEquals($expected, $condition($submissionValues));
    }

    public static function invokeDataProvider(): array
    {
        return [
            'should show' => [
                'show' => true,
                'when' => 'otherField',
                'equalTo' => 'Yes',
                'submissionValues' => ['otherField' => 'Yes'],
                'expected' => true,
            ],
            'should hide' => [
                'show' => true,
                'when' => 'otherField',
                'equalTo' => 'Yes',
                'submissionValues' => ['otherField' => 'No'],
                'expected' => false,
            ],
            'inverse, should show' => [
                'show' => false,
                'when' => 'otherField',
                'equalTo' => 'Yes',
                'submissionValues' => ['otherField' => 'Yes'],
                'expected' => false,
            ],
            'inverse, should not show' => [
                'show' => false,
                'when' => 'otherField',
                'equalTo' => 'Yes',
                'submissionValues' => ['otherField' => 'No'],
                'expected' => true,
            ],
            'Missing value handled' => [
                'show' => true,
                'when' => 'otherField',
                'equalTo' => 'Yes',
                'submissionValues' => [],
                'expected' => false,
            ],
            'Select boxes values handled' => [
                'show' => true,
                'when' => 'otherField',
                'equalTo' => 'Yes',
                'submissionValues' => [
                    'otherField' => [
                        'Yes' => true,
                        'No' => false,
                    ],
                ],
                'expected' => true,
            ],
            'Select dropdown values handled' => [
                'show' => true,
                'when' => 'otherField',
                'equalTo' => 'Yes',
                'submissionValues' => [
                    'otherField' => [
                        'Yes',
                    ],
                ],
                'expected' => true,
            ],
        ];
    }
}
