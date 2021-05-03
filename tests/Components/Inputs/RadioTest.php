<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Radio;
use Northwestern\SysDev\DynamicForms\Tests\Components\TestCases\InputComponentTestCase;

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

    public function submissionValueProvider(): array
    {
        $checkboxes = ['foo' => true, 'bar' => true];

        return [
            'no transformations' => [null, $checkboxes, $checkboxes],
            'upper' => [CaseEnum::UPPER, $checkboxes, $checkboxes],
            'lower' => [CaseEnum::LOWER, $checkboxes, $checkboxes],
        ];
    }
}
