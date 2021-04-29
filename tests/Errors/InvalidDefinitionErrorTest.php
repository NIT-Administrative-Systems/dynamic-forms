<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Errors;

use Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError;
use Orchestra\Testbench\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError
 */
class InvalidDefinitionErrorTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->expectException(InvalidDefinitionError::class);
        $this->expectExceptionMessage('[foo.bar] Test');

        throw new InvalidDefinitionError('Test', 'foo.bar');
    }
}
