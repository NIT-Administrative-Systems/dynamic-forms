<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Errors;

use Northwestern\SysDev\DynamicForms\Errors\UnknownComponentError;
use Orchestra\Testbench\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Errors\UnknownComponentError
 */
class UnknownComponentErrorTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->expectException(UnknownComponentError::class);

        throw new UnknownComponentError('Foo');
    }
}
