<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\Inputs\Signature;
use Northwestern\SysDev\DynamicForms\Tests\Components\InputComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\Signature
 */
class SignatureTest extends InputComponentTestCase
{
    protected string $componentClass = Signature::class;

    public function validationsProvider(): array
    {
        return [
            'garbage data fails' => [[], 'not an image', false],
            'valid data passes' => [[], 'data:image/png;base64,iVBORw0KG...', true],
            'required passes' => [['required' => true], 'data:image/png;base64,iVBORw0KG...', true],
            'required fails' => [['required' => true], '', false],
        ];
    }
}
