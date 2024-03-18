<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Storage\Concerns;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Northwestern\SysDev\DynamicForms\Storage\Concerns\HandlesDynamicFormsStorage;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Storage\Concerns\LocalStorage
 */
final class LocalStorageTest extends \Orchestra\Testbench\TestCase
{
    /**
     * @covers ::storeURL
     */
    public function testUploadWorks(): void
    {
        $this->app['router']->post(__METHOD__, function (Request $request) {
            return $this->mock_controller()->storeURL($request);
        });

        $response = $this->postJson(__METHOD__, [
            'file' => new UploadedFile(implode(DIRECTORY_SEPARATOR, [__DIR__, '../..', 'Fixtures', 'sample.pdf']), 'sample.pdf', null, null, true),
            'name' => 'sample.pdf',
        ]);
        $response->assertOk();
    }

    /**
     * @covers ::storeURL
     */
    public function testUploadAuthorization(): void
    {
        $this->app['router']->post(__METHOD__, function (Request $request) {
            return $this->mock_controller(fn () => throw new AuthorizationException('fail'))->storeURL($request);
        });

        $response = $this->post(__METHOD__, ['name' => 'testFile.docx']);
        $response->assertForbidden();
    }

    /**
     * @covers ::showURL
     */
    public function testDownloadAuthorization(): void
    {
        $this->app['router']->get(__METHOD__, function (Request $request) {
            return $this->mock_controller(fn () => throw new AuthorizationException('fail'))->showURL($request);
        });
        $response = $this->call('GET', __METHOD__, ['form' => 'sample.pdf']);
        $response->assertForbidden();
    }

    /**
     * @covers ::deleteURL
     */
    public function testDeleteAuthorization(): void
    {
        $this->app['router']->delete(__METHOD__, function (Request $request) {
            return $this->mock_controller(fn () => throw new AuthorizationException('fail'))->deleteURL($request);
        });
        $response = $this->call('DELETE', __METHOD__, ['form' => 'sample.pdf']);
        $response->assertForbidden();
    }

    /**
     * Gets a class using the HandlesDynamicFormsStorage trait w/ the authorizeFileAction method implemented.
     *
     * @param callable|null $authCallback A function that throws an AuthorizationException
     * @return object Controller w/ authorizeFileAction() method implemented
     */
    protected function mock_controller(?callable $authCallback = null): object
    {
        $authCallback = $authCallback ?: fn () => true;

        return new class($authCallback) {
            use HandlesDynamicFormsStorage;

            public function __construct(protected $authCallback)
            {
                //
            }

            protected function authorizeFileAction(string $action, string $fileKey, Request $request, string $backend): void
            {
                ($this->authCallback)();
            }
        };
    }
}
