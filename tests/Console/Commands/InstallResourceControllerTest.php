<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Console\Commands;

use Northwestern\SysDev\DynamicForms\Console\Commands\InstallResourceController;
use Orchestra\Testbench\TestCase;
use ReflectionMethod;


/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Console\Commands\InstallResourceController
 */
class InstallResourceControllerTest extends TestCase
{
    /**
     * @covers ::getStub
     * @covers ::getDefaultNamespace
     * @covers ::getNameInput
     */
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
