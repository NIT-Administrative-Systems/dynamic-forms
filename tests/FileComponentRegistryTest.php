<?php

namespace Northwestern\SysDev\DynamicForms\Tests;

use Northwestern\SysDev\DynamicForms\FileComponentRegistry;
use Northwestern\SysDev\DynamicForms\Storage\S3Driver;
use Orchestra\Testbench\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\FileComponentRegistry
 */
class FileComponentRegistryTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::registerDefaults
     * @covers ::register
     * @covers ::registered
     */
    public function testsRegistration(): void
    {
        $registry = new FileComponentRegistry();
        $this->assertGreaterThan(0, count($registry->registered()));
    }

    /**
     * @covers ::get
     */
    public function testGet(): void
    {
        $registry = new FileComponentRegistry();
        $this->assertEquals(S3Driver::class, $registry->get('s3'));
    }
}
