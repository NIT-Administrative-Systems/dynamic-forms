<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Console\Commands;

use Northwestern\SysDev\DynamicForms\Console\Commands\InstallStorageController;
use Orchestra\Testbench\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Console\Commands\InstallStorageController
 */
class InstallStorageControllerTest extends TestCase
{
    /**
     * @covers ::getStub
     * @covers ::getDefaultNamespace
     * @covers ::getNameInput
     */
    public function testGeneratorMethods(): void
    {
        $cmd = $this->app->make(InstallStorageController::class);

        $this->assertStringContainsString('Controller', $this->invokeProtected($cmd, 'getNameInput'));
        $this->assertStringContainsString('stubs', $this->invokeProtected($cmd, 'getStub'));
        $this->assertEquals('App\Http\Controllers', $this->invokeProtected($cmd, 'getDefaultNamespace', ['App']));
    }
}
