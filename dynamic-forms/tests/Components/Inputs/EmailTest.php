<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\Inputs\Email;
use Northwestern\SysDev\DynamicForms\Tests\Components\InputComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\Email
 */
class EmailTest extends InputComponentTestCase
{
    protected string $componentClass = Email::class;

    public function validationsProvider(): array
    {
        return [
            'passes when no value is supplied' => [[], '', true],
            'invalid email fails' => [[], 'dog', false],
            'valid email passes' => [[], 'foo@bar.com', true],
            'required passes' => [['required' => true], 'foo@bar.com', true],
            'required fails' => [['required' => true], '', false],
            'minLength fails' => [['minLength' => 50], 'foo@bar.com', false],
            'minLength passes' => [['minLength' => 5], 'foo@bar.com', true],
            'maxLength fails' => [['maxLength' => 3], 'foo@bar.com', false],
            'maxLength passes' => [['maxLength' => 300], 'foo@bar.com', true],
            'minWords fails' => [['minWords' => 2], 'foo@bar.com', false],
            'minWords passes' => [['minWords' => 1], 'foo@bar.com', true],
            'regex passes' => [['pattern' => 'reddit\.com'], 'foo@reddit.com', true],
            'regex fails' => [['pattern' => 'reddit\.com'], 'foo@bar.com', false],
        ];
    }
}
