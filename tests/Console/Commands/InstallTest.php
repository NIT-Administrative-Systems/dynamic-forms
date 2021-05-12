<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Console\Commands;

use Northwestern\SysDev\DynamicForms\Console\Commands\Install;
use Orchestra\Testbench\TestCase;
use ReflectionMethod;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Console\Commands\Install
 */
class InstallTest extends TestCase
{
    /**
     * @covers ::getStub
     * @covers ::getDefaultNamespace
     * @covers ::getNameInput
     */
    public function testGeneratorMethods(): void
    {
        $cmd = $this->installCommand();

        $this->assertStringContainsString('Controller', $this->invokeProtected($cmd, 'getNameInput'));
        $this->assertStringContainsString('stubs', $this->invokeProtected($cmd, 'getStub'));
        $this->assertEquals('App\Http\Controllers', $this->invokeProtected($cmd, 'getDefaultNamespace', ['App']));
    }

    /**
     * @covers ::ejectRoutes
     */
    public function testEjectRoutes(): void
    {
        $cmd = $this->installCommand();
        $file = $this->tempFile();

        $this->invokeProtected($cmd, 'ejectRoutes', [$file]);

        $this->assertNotEmpty(file_get_contents($file));
    }

    /**
     * @covers ::ejectJsInclude
     */
    public function testEjectJsInclude(): void
    {
        $cmd = $this->installCommand();
        $file = $this->tempFile();

        $this->invokeProtected($cmd, 'ejectJsInclude', [$file]);

        $this->assertNotEmpty(file_get_contents($file));
    }

    /**
     * @covers ::ejectCssInclude
     */
    public function testEjectCssInclude(): void
    {
        $cmd = $this->installCommand();
        $file = $this->tempFile();

        $this->invokeProtected($cmd, 'ejectCssInclude', [$file]);

        $this->assertNotEmpty(file_get_contents($file));
    }

    /**
     * @covers ::updatePackages
     */
    public function testUpdatePackages()
    {
        $cmd = $this->installCommand();

        $file = $this->tempFile();
        $json = json_encode(['devDependencies' => []]);
        file_put_contents($file, $json);

        $this->invokeProtected($cmd, 'updatePackages', [$file]);

        $this->assertStringContainsString(
            sprintf('"formiojs": "%s"', Install::FORMIOJS_VERSION),
            file_get_contents($file)
        );
    }

    protected function installCommand(): Install
    {
        return $this->app->make(Install::class);
    }

    protected function invokeProtected(Install $cmd, string $method, $args = []): mixed
    {
        $refMeth = new ReflectionMethod($cmd, $method);
        $refMeth->setAccessible(true);

        return $refMeth->invoke($cmd, ...$args);
    }

    /**
     * Returns path to a temporary file.
     *
     * The file should be cleaned up automatically when the process exits.
     */
    protected function tempFile(): string
    {
        $file = tmpfile();

        return stream_get_meta_data($file)['uri'];
    }
}
