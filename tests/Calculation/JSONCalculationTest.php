<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Calculation;

use Monolog\Test\TestCase;
use Northwestern\SysDev\DynamicForms\Calculation\JSONCalculation;

#[\PHPUnit\Framework\Attributes\CoversClass(\Northwestern\SysDev\DynamicForms\Calculation\JSONCalculation::class)]
final class JSONCalculationTest extends TestCase
{
    public function testCalculation(): void
    {
        $calc = new JSONCalculation(['+' => [1, 2]]);
        $this->assertEquals(3, $calc([]));
    }
}
