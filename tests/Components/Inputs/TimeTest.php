<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\Inputs\Time;
use Northwestern\SysDev\DynamicForms\Tests\Components\InputComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\Time
 */
class TimeTest extends InputComponentTestCase
{
    protected string $componentClass = Time::class;

    public function validationsProvider(): array
    {
        return [
            'empty value passes' => [[], '', true],
            'valid data passes' => [[], '12:00:00', true],
            'invalid data fails' => [[], '24:00:00', false],
            'required passes' => [['required' => true], '01:00:00', true],
            'required fails' => [['required' => true], '', false],
        ];
    }
}
