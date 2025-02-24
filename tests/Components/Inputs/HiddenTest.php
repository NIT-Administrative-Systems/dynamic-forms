<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Hidden;
use Northwestern\SysDev\DynamicForms\Tests\Components\TestCases\InputComponentTestCase;

#[\PHPUnit\Framework\Attributes\CoversClass(\Northwestern\SysDev\DynamicForms\Components\Inputs\Hidden::class)]
class HiddenTest extends InputComponentTestCase
{
    protected string $componentClass = Hidden::class;

    public static function validationsProvider(): array
    {
        return [
            'no data' => [[], null, true],
            'pass through' => [[], 'yep', true],
        ];
    }

    public static function submissionValueProvider(): array
    {
        return [
            'no transformations' => [null, 'foo', 'foo'],
            'upper' => [CaseEnum::UPPER, 'foo', 'FOO'],
            'lower' => [CaseEnum::LOWER, 'Foo', 'foo'],
        ];
    }
}
