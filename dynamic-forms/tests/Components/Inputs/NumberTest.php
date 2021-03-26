<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\Inputs\Number;
use Northwestern\SysDev\DynamicForms\Tests\Components\InputComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\Number
 */
class NumberTest extends InputComponentTestCase
{
    protected string $componentClass = Number::class;

    public function validationsProvider(): array
    {
        return [
            'required passes' => [['required' => true], 1.0, true],
            'required fails' => [['required' => true], '', false],
            'min fails' => [['min' => 10], 9, false],
            'min passes' => [['min' => 10], 11, true],
            'max fails' => [['max' => 3], 4, false],
            'max passes' => [['max' => 3], 3, true],
        ];
    }
}
