<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Checkbox;
use Northwestern\SysDev\DynamicForms\Tests\Components\InputComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\Checkbox
 */
class CheckboxTest extends InputComponentTestCase
{
    protected string $componentClass = Checkbox::class;

    public function validationsProvider(): array
    {
        return [
            'required passes' => [['required' => true], true, true],
            'required fails' => [['required' => true], false, false],
        ];
    }

    public function submissionValueProvider(): array
    {
        return [
            'no transformations' => [null, true, true],
            'upper' => [CaseEnum::UPPER, true, true],
            'lower' => [CaseEnum::LOWER, true, true],
        ];
    }
}
