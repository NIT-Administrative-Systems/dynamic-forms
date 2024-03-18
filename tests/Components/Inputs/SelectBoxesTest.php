<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\SelectBoxes;
use Northwestern\SysDev\DynamicForms\Tests\Components\TestCases\InputComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\SelectBoxes
 */
final class SelectBoxesTest extends InputComponentTestCase
{
    protected string $componentClass = SelectBoxes::class;
    protected array $defaultAdditional = [
        'values' => [
            ['label' => 'Foo', 'value' => 'foo', 'shortcut' => ''],
            ['label' => 'Boo', 'value' => 'bar', 'shortcut' => ''],
        ],
    ];

    /**
     * @covers ::validate
     * @covers ::submissionValue
     */
    public function testInvalidOptionsAreExcluded(): void
    {
        // By setting the minimum to 1 and checking an invalid checkbox off,
        // this SHOULD fail since 'dog' (the invalid option) is removed from
        // consideration
        $component = $this->getComponent(
            validations: ['required' => true],
            additional: $this->defaultAdditional,
            submissionValue: [
                'foo' => false,
                'bar' => false,
                'dog' => true,
            ],
        );

        $bag = $component->validate();
        $this->assertTrue($bag->isNotEmpty());
        $this->assertEquals(['foo' => false, 'bar' => false], $component->submissionValue());
    }

    /**
     * @covers ::submissionValue
     */
    public function testMultipleSubmissionValues(): void
    {
        $component = $this->getComponent(
            hasMultipleValues: true,
            submissionValue: [
                [
                    'foo' => false,
                    'bar' => false,
                    'dog' => true,
                ],
                [
                    'foo' => true,
                    'bar' => true,
                    'dog' => true,
                ],
            ]
        );

        $expected = [
            [
                'foo' => false,
                'bar' => false,
            ],
            [
                'foo' => true,
                'bar' => true,
            ],
        ];

        $this->assertEquals($expected, $component->submissionValue());
    }

    public static function validationsProvider(): array
    {
        return [
            'required passes' => [['required' => true], ['foo' => true, 'bar' => true], true],
            'required fails' => [['required' => true], ['foo' => false, 'bar' => false], false],
            'min passes' => [['minSelectedCount' => 2], ['foo' => true, 'bar' => true], true],
            'min fails' => [['minSelectedCount' => 2], ['foo' => true, 'bar' => false], false],
            'max passes' => [['maxSelectedCount' => 1], ['foo' => true, 'bar' => false], true],
            'max fails' => [['maxSelectedCount' => 1], ['foo' => true, 'bar' => true], false],
        ];
    }

    public static function submissionValueProvider(): array
    {
        $boxes = ['foo' => true, 'bar' => false];

        return [
            'no transformations' => [null, $boxes, $boxes],
            'upper' => [CaseEnum::UPPER, $boxes, $boxes],
            'lower' => [CaseEnum::LOWER, $boxes, $boxes],
        ];
    }
}
