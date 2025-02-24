<?php

namespace Northwestern\SysDev\DynamicForms\Tests;

use Northwestern\SysDev\DynamicForms\FileComponentRegistry;
use Northwestern\SysDev\DynamicForms\Storage\S3Driver;
use Orchestra\Testbench\TestCase;

#[\PHPUnit\Framework\Attributes\CoversClass(\Northwestern\SysDev\DynamicForms\FileComponentRegistry::class)]
final class FileComponentRegistryTest extends TestCase
{
    public function testsRegistration(): void
    {
        $registry = new FileComponentRegistry();
        $this->assertGreaterThan(0, count($registry->registered()));
    }

    public function testGet(): void
    {
        $registry = new FileComponentRegistry();
        $this->assertEquals(S3Driver::class, $registry->get('s3'));
    }
}
