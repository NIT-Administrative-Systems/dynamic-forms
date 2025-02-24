<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Console\Commands;

use Northwestern\SysDev\DynamicForms\Console\Commands\InstallResourceController;
use Orchestra\Testbench\TestCase;
use ReflectionMethod;

#[\PHPUnit\Framework\Attributes\CoversClass(\Northwestern\SysDev\DynamicForms\Console\Commands\InstallResourceController::class)]
final class InstallResourceControllerTest extends TestCase
{
    public function testGeneratorMethods(): void
    {
        $cmd = $this->app->make(InstallResourceController::class);

        $this->assertStringContainsString('Controller', $this->invokeProtected($cmd, 'getNameInput'));
        $this->assertStringContainsString('stubs', $this->invokeProtected($cmd, 'getStub'));
        $this->assertEquals('App\Http\Controllers', $this->invokeProtected($cmd, 'getDefaultNamespace', ['App']));
    }

    protected function invokeProtected(InstallResourceController $cmd, string $method, $args = []): mixed
    {
        $refMeth = new ReflectionMethod($cmd, $method);
        $refMeth->setAccessible(true);

        return $refMeth->invoke($cmd, ...$args);
    }
}
