<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\Inputs\Radio;
use Northwestern\SysDev\DynamicForms\Tests\Components\InputComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\Radio
 */
class RadioTest extends InputComponentTestCase
{
    protected string $componentClass = Radio::class;
    protected array $defaultAdditional = [
        'values' => [
            ['label' => 'Foo', 'value' => 'foo', 'shortcut' => ''],
            ['label' => 'Bar', 'value' => 'bar', 'shortcut' => ''],
        ],
    ];

    public function validationsProvider(): array
    {
        return [
            'passes with no data' => [[], '', true],
            'fails with invalid data' => [[], 'invalid option', false],
            'required passes' => [['required' => true], 'foo', true],
            'required fails' => [['required' => true], '', false],
        ];
    }
}
