<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Hidden;
use Northwestern\SysDev\DynamicForms\Tests\Components\TestCases\InputComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\Hidden
 */
class HiddenTest extends InputComponentTestCase
{
    protected string $componentClass = Hidden::class;

    public function validationsProvider(): array
    {
        return [
            'no data' => [[], null, true],
            'pass through' => [[], 'yep', true],
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
