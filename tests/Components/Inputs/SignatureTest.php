<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Signature;
use Northwestern\SysDev\DynamicForms\Tests\Components\TestCases\InputComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\Signature
 */
class SignatureTest extends InputComponentTestCase
{
    protected string $componentClass = Signature::class;

    public static function validationsProvider(): array
    {
        return [
            'garbage data fails' => [[], 'not an image', false],
            'valid data passes' => [[], 'data:image/png;base64,iVBORw0KG...', true],
            'required passes' => [['required' => true], 'data:image/png;base64,iVBORw0KG...', true],
            'required fails' => [['required' => true], '', false],
        ];
    }

    public static function submissionValueProvider(): array
    {
        $data = 'data:image/png;base64,iVBORw0KG...';

        return [
            'no transformations' => [null, $data, $data],
            'upper' => [CaseEnum::UPPER, $data, $data],
            'lower' => [CaseEnum::LOWER, $data, $data],
        ];
    }
}
