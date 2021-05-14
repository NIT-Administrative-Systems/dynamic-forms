<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Storage\Concerns;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Northwestern\SysDev\DynamicForms\DynamicFormsProvider;
use Northwestern\SysDev\DynamicForms\Storage\Concerns\HandlesDynamicFormsStorage;
use Northwestern\SysDev\DynamicForms\Storage\S3Driver;
use Orchestra\Testbench\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Storage\Concerns\HandlesDynamicFormsStorage
 */
class HandlesDynamicFormsStorageTest extends TestCase
{
    /**
     * @covers ::storeS3
     * @covers ::storageDriver
     */
    public function testUploadWorks(): void
    {
        $this->app->singleton(S3Driver::class, function ($app) {
            $mock = $this->createStub(S3Driver::class);

            $mock->method('getUploadLink')->willReturn(response()->json([
                'signed' => 'https://signed-url.example.net/foobarbaz',
                'headers' => ['Content-Type' => 'application/octet-stream'],
                'url' => url('/'),
                'data' => ['fileName' => 'testFile.docx'],
            ]));

            return $mock;
        });

        $this->app['router']->post(__METHOD__, function (Request $request) {
            return $this->mock_controller()->storeS3($request);
        });

        $response = $this->post(__METHOD__, ['name' => 'testFile.docx']);
        $response->assertOk()->assertJsonStructure([
            'signed',
            'headers',
            'url',
            'data' => [
                'fileName',
            ],
        ]);
    }

    /**
     * @covers ::store
     */
    public function testUploadAuthorization(): void
    {
        $this->app['router']->post(__METHOD__, function (Request $request) {
            return $this->mock_controller(fn () => throw new AuthorizationException('fail'))->store($request);
        });

        $response = $this->post(__METHOD__, ['name' => 'testFile.docx']);
        $response->assertForbidden();
    }

    /**
     * @covers ::showS3
     */
    public function testDownloadAuthorization(): void
    {
        $this->app['router']->get(__METHOD__.'/{fileKey}', function (Request $request, $fileKey) {
            return $this->mock_controller(fn () => throw new AuthorizationException('fail'))->show($request, $fileKey);
        });

        $response = $this->get(__METHOD__.'/testFile.docx');
        $response->assertForbidden();
    }

    /**
     * @covers ::showS3
     * @covers ::storageDriver
     */
    public function testDownloadJsonResponse(): void
    {
        $url = 'https://download.example.com';

        $this->app->singleton(S3Driver::class, function ($app) use ($url) {
            $mock = $this->createStub(S3Driver::class);
            $mock->method('getDownloadLink')->willReturn(response()->json(['url' => $url]));

            return $mock;
        });

        $this->app['router']->get(__METHOD__.'', function (Request $request) {
            return $this->mock_controller()->showS3($request, null);
        });

        $response = $this->get(__METHOD__.'?key=testFile.docx');
        $response->assertOk()->assertJson(['url' => $url]);
    }

    /**
     * @covers ::showS3
     * @covers ::storageDriver
     */
    public function testDownloadRedirect(): void
    {
        $url = 'https://download.example.com';

        $this->app->singleton(S3Driver::class, function ($app) use ($url) {
            $mock = $this->createStub(S3Driver::class);
            $mock->method('getDirectDownloadLink')->willReturn($url);

            return $mock;
        });


        $this->app['router']->get(__METHOD__.'/{fileKey}', function (Request $request, $fileKey) {
            return $this->mock_controller()->showS3($request, $fileKey);
        });

        $response = $this->get(__METHOD__.'/testFile.docx');
        $response->assertRedirect($url);
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

    protected function getPackageProviders($app): array
    {
        return [DynamicFormsProvider::class];
    }
}
