<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Errors;

use Monolog\Test\TestCase;
use Northwestern\SysDev\DynamicForms\Errors\CalculationNotImplemented;

#[\PHPUnit\Framework\Attributes\CoversClass(\Northwestern\SysDev\DynamicForms\Errors\CalculationNotImplemented::class)]
final class CalculationNotImplementedTest extends TestCase
{
    public function testThrows(): void
    {
        $this->expectException(CalculationNotImplemented::class);
        $this->expectExceptionMessage('test');

        throw new CalculationNotImplemented('test', CalculationNotImplemented::JSON);
    }
}
