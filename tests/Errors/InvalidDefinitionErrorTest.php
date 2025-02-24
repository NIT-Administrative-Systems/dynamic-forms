<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Errors;

use Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError;
use Orchestra\Testbench\TestCase;

#[\PHPUnit\Framework\Attributes\CoversClass(\Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError::class)]
final class InvalidDefinitionErrorTest extends TestCase
{
    public function testConstruct(): void
    {
        $this->expectException(InvalidDefinitionError::class);
        $this->expectExceptionMessage('[foo.bar] Test');

        throw new InvalidDefinitionError('Test', 'foo.bar');
    }
}
