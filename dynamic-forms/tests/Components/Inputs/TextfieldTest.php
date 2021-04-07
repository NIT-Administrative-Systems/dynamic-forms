<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Textfield;
use Northwestern\SysDev\DynamicForms\Tests\Components\InputComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\Textfield
 */
class TextfieldTest extends InputComponentTestCase
{
    protected string $componentClass = Textfield::class;

    public function validationsProvider(): array
    {
        return [
            'required passes' => [['required' => true], 'Present', true],
            'required fails' => [['required' => true], '', false],
            'minLength fails' => [['minLength' => 10], 'Four', false],
            'minLength passes' => [['minLength' => 4], 'Four', true],
            'maxLength fails' => [['maxLength' => 3], 'Four', false],
            'maxLength passes' => [['maxLength' => 3], 'The', true],
            'minWords fails' => [['minWords' => 2], 'One', false],
            'minWords passes' => [['minWords' => 2], 'Two words', true],
            'regex passes' => [['pattern' => '[a-z|A-Z]+'], 'Good', true],
            'regex handles bars' => [['pattern' => '|'], 're|bar', true],
            'regex handles slashes' => [['pattern' => '/'], 'http://', true],
            'regex fails' => [['pattern' => '^[0-9]$'], '111 Dog', false],
            'customMessage respected' => [['required' => true, 'minWords' => 2, 'customMessage' => 'message!'], '', false, 'message!'],
        ];
    }

    public function submissionValueProvider(): array
    {
        return [
            'no transformations' => [null, 'foo', 'foo'],
            'upper' => [CaseEnum::UPPER, 'foo', 'FOO'],
            'lower' => [CaseEnum::LOWER, 'Foo', 'foo'],
        ];
    }
}
