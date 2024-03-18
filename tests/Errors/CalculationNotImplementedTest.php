<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Errors;

use Monolog\Test\TestCase;
use Northwestern\SysDev\DynamicForms\Errors\CalculationNotImplemented;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Errors\CalculationNotImplemented
 */
final class CalculationNotImplementedTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testThrows(): void
    {
        $this->expectException(CalculationNotImplemented::class);
        $this->expectExceptionMessage('test');

        throw new CalculationNotImplemented('test', CalculationNotImplemented::JSON);
    }
}
