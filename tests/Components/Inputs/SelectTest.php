<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\Inputs\Select;
use Northwestern\SysDev\DynamicForms\Tests\Components\InputComponentTestCase;

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
            ],
        ],
    ];

    public function validationsProvider(): array
    {
        return [
            'not required passes' => [[], '', true],
            'required passes' => [['required' => true], 'foo', true],
            'required fails' => [['required' => true], '', false],
            'invalid values always rejected' => [[], 'not a valid value', false],
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
        $this->assertEquals(['foo', 'bar'], $this->getComponent()->optionValues());
    }
}
