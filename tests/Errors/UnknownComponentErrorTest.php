<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Errors;

use Northwestern\SysDev\DynamicForms\Errors\UnknownComponentError;
use Orchestra\Testbench\TestCase;

#[\PHPUnit\Framework\Attributes\CoversClass(\Northwestern\SysDev\DynamicForms\Errors\UnknownComponentError::class)]
final class UnknownComponentErrorTest extends TestCase
{
    public function testConstruct(): void
    {
        $this->expectException(UnknownComponentError::class);

        throw new UnknownComponentError('Foo');
    }
}
