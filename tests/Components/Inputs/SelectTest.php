<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Select;
use Northwestern\SysDev\DynamicForms\Tests\Components\TestCases\InputComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\Select
 */
class SelectTest extends InputComponentTestCase
{
    protected string $componentClass = Select::class;
    protected array $defaultAdditional = [
        'dataSrc' => 'values',
        'data' => [
            'values' => [
                ['label' => 'Foo', 'value' => 'foo'],
                ['label' => 'Bar', 'value' => 'bar'],
                ['label' => 'Number', 'value' => '1', 'shortcut' => ''],
            ],
        ],
    ];

    /**
     * @covers ::processValidations
     * @covers ::validate
     */
    public function testValidationInMultipleModeWithNull(): void
    {
        $component = $this->getComponent(
            hasMultipleValues: true,
            submissionValue: null,
        );

        $bag = $component->validate();
        $this->assertEquals(true, $bag->isEmpty());
    }

    public function validationsProvider(): array
    {
        return [
            'not required passes' => [[], '', true],
            'required passes' => [['required' => true], 'foo', true],
            'required fails' => [['required' => true], '', false],
            'invalid values always rejected' => [[], 'not a valid value', false],
            'passes with integer' => [['required' => true], 1, true],
        ];
    }

    public function submissionValueProvider(): array
    {
        return [
            'no transformations' => [null, 'foo', 'foo'],
            'integer' => [null, 1, '1'],
            'upper' => [CaseEnum::UPPER, 'foo', 'foo'],
            'lower' => [CaseEnum::LOWER, 'foo', 'foo'],
        ];
    }

    /**
     * @covers ::dataSource
     */
    public function testDataSource(): void
    {
        $this->assertEquals(Select::DATA_SRC_VALUES, $this->getComponent()->dataSource());
    }

    /**
     * @covers ::optionValues
     */
    public function testOptionValues(): void
    {
        $this->assertEquals(['foo', 'bar', 1], $this->getComponent()->optionValues());
    }
}
