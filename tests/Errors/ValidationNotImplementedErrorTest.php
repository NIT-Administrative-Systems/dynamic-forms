<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Errors;

use Northwestern\SysDev\DynamicForms\Errors\ValidationNotImplementedError;
use Orchestra\Testbench\TestCase;

#[\PHPUnit\Framework\Attributes\CoversClass(\Northwestern\SysDev\DynamicForms\Errors\ValidationNotImplementedError::class)]
final class ValidationNotImplementedErrorTest extends TestCase
{
    public function testConstruct(): void
    {
        $this->expectException(ValidationNotImplementedError::class);

        throw new ValidationNotImplementedError('Foo');
    }
}
