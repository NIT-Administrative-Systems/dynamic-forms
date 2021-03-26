<?php

namespace Northwestern\SysDev\DynamicForms\Tests;

use Northwestern\SysDev\DynamicForms\ComponentRegistry;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Textfield;
use Tests\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\ComponentRegistry
 */
class ComponentRegistryTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::registerDefaults
     * @covers ::register
     * @covers ::registered
     */
    public function testsRegistration(): void
    {
        $registry = new ComponentRegistry();
        $this->assertGreaterThan(0, count($registry->registered()));
    }

    /**
     * @covers ::get
     */
    public function testGet(): void
    {
        $registry = new ComponentRegistry();
        $this->assertEquals('\\'.Textfield::class, $registry->get('textfield'));
    }
}
