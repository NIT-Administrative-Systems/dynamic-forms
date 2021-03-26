<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Errors;

use Northwestern\SysDev\DynamicForms\Errors\ValidationNotImplementedError;
use Tests\TestCase;

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
