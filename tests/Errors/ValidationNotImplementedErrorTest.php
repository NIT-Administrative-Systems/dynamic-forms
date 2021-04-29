<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Errors;

use Northwestern\SysDev\DynamicForms\Errors\ValidationNotImplementedError;
use Orchestra\Testbench\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Errors\ValidationNotImplementedError
 */
class ValidationNotImplementedErrorTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->expectException(ValidationNotImplementedError::class);

        throw new ValidationNotImplementedError('Foo');
    }
}
