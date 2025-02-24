<?php

namespace Northwestern\SysDev\DynamicForms\Tests;

use Northwestern\SysDev\DynamicForms\ComponentRegistry;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Textfield;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(\Northwestern\SysDev\DynamicForms\ComponentRegistry::class)]
final class ComponentRegistryTest extends TestCase
{
    public function testsRegistration(): void
    {
        $registry = new ComponentRegistry();
        $this->assertGreaterThan(0, count($registry->registered()));
    }

    public function testGet(): void
    {
        $registry = new ComponentRegistry();
        $this->assertEquals(Textfield::class, $registry->get('textfield'));
    }
}
