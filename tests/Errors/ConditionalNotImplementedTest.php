<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Errors;

use Northwestern\SysDev\DynamicForms\Errors\ConditionalNotImplemented;
use Orchestra\Testbench\TestCase;

#[\PHPUnit\Framework\Attributes\CoversClass(\Northwestern\SysDev\DynamicForms\Errors\ConditionalNotImplemented::class)]
final class ConditionalNotImplementedTest extends TestCase
{
    public function testThrows(): void
    {
        $this->expectException(ConditionalNotImplemented::class);
        $this->expectExceptionMessage('test');

        throw new ConditionalNotImplemented('test', ConditionalNotImplemented::JSON);
    }
}
