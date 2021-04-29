<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Errors;

use Northwestern\SysDev\DynamicForms\Errors\ConditionalNotImplemented;
use Orchestra\Testbench\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Errors\ConditionalNotImplemented
 */
class ConditionalNotImplementedTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testThrows(): void
    {
        $this->expectException(ConditionalNotImplemented::class);
        $this->expectExceptionMessage('test');

        throw new ConditionalNotImplemented('test', ConditionalNotImplemented::JSON);
    }
}
