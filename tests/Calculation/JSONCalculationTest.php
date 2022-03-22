<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Calculation;

use Monolog\Test\TestCase;
use Northwestern\SysDev\DynamicForms\Calculation\JSONCalculation;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Calculation\JSONCalculation
 */
class JSONCalculationTest extends TestCase
{
    /**
     * @covers ::__invoke
     */
    public function testCalculation(): void
    {
        $calc = new JSONCalculation(['+' => [1, 2]]);
        $this->assertEquals(3, $calc([]));
    }
}
