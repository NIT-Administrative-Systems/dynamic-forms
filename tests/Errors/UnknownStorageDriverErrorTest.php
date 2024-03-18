<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Errors;

use Northwestern\SysDev\DynamicForms\Errors\UnknownStorageDriverError;
use Orchestra\Testbench\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Errors\UnknownStorageDriverError
 */
class UnknownStorageDriverErrorTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testConstruct(): void
    {
        $this->expectException(UnknownStorageDriverError::class);

        throw new UnknownStorageDriverError('Foo');
    }
}
