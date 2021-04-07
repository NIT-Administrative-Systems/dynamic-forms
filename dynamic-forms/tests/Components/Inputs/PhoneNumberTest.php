<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\PhoneNumber;
use Northwestern\SysDev\DynamicForms\Tests\Components\InputComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\PhoneNumber
 */
class PhoneNumberTest extends InputComponentTestCase
{
    protected string $componentClass = PhoneNumber::class;

    public function validationsProvider(): array
    {
        return [
            'passes when no value is supplied' => [[], '', true],
            'required passes' => [['required' => true], '(203) 777-7777', true],
            'required false' => [['required' => true], '', false],
        ];
    }

    public function submissionValueProvider(): array
    {
        $number = '(203) 777-7777';

        return [
            'no transformations' => [null, $number, $number],
            'upper' => [CaseEnum::UPPER, $number, $number],
            'lower' => [CaseEnum::LOWER, $number, $number],
        ];
    }
}
