<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Errors;

use Northwestern\SysDev\DynamicForms\Errors\UnknownStorageDriverError;
use Orchestra\Testbench\TestCase;

#[\PHPUnit\Framework\Attributes\CoversClass(\Northwestern\SysDev\DynamicForms\Errors\UnknownStorageDriverError::class)]
final class UnknownStorageDriverErrorTest extends TestCase
{
    public function testConstruct(): void
    {
        $this->expectException(UnknownStorageDriverError::class);

        throw new UnknownStorageDriverError('Foo');
    }
}
