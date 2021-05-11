<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Rules;

use Northwestern\SysDev\DynamicForms\Errors\UnknownStorageDriverError;
use Northwestern\SysDev\DynamicForms\Rules\FileExists;
use Northwestern\SysDev\DynamicForms\Storage\StorageInterface;
use Orchestra\Testbench\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Rules\FileExists
 */
class FileExistsTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::passes
     * @dataProvider passesProvider
     */
    public function testPasses(array $file, bool $shouldExist, bool $passes): void
    {
        $rule = $this->rule($shouldExist);

        $this->app['router']
            ->get('/dynamic-forms/storage/s3/{fileKey}')
            ->name('dynamic-forms.file-redirect');


        $this->app['router']
            ->get('/dynamic-forms/storage/url/')
            ->name('dynamic-forms.file-download');

        $this->assertEquals($passes, $rule->passes('test', $file));
    }

    public function passesProvider(): array
    {
        $validS3 = [
            'name' => 'foo1',
            'key' => 'foo1', // should match name
            'url' => '/dynamic-forms/storage/s3/foo1', // should match name
            'storage' => FileExists::STORAGE_S3,
        ];

        $validURL = [
            'name' => 'foo1',
            'url' => 'http://localhost/dynamic-forms/storage/url?baseUrl=https%3A%2F%2Fapi.form.io&project=&form=/foo1', // should match name with additional data fields added on
            'storage' => FileExists::STORAGE_URL,
            'data' => ['baseUrl' => 'https://api.form.io',
                        'project' => '',
                        'form' => '',]
        ];

        return [
            // file, should exist in storage, passes
            'valid S3' => [$validS3, true, true],
            'missing field S3' => [['storage' => FileExists::STORAGE_S3], true, false],
            'unexpected url S3' => [array_merge($validS3, ['url' => '/dog']), true, false],
            'not consistent S3' => [array_merge($validS3, ['name' => 'dog']), true, false],
            'file does not exist S3' => [$validS3, false, false],

            // file, should exist in storage, passes
            'valid URL' => [$validURL, true, true],
            'missing field URL' => [['storage' => FileExists::STORAGE_URL], true, false],
            'unexpected url URL' => [array_merge($validURL, ['url' => '/dog']), true, false],
            'file does not exist URL' => [$validURL, false, false],
        ];
    }

    /**
     * @covers ::passes
     * @dataProvider passesThrowsExceptionProvider
     */
    public function testPassesThrowsException(array $file): void
    {
        $this->expectException(UnknownStorageDriverError::class);

        $this->rule(true)->passes('test', $file);
    }

    public function passesThrowsExceptionProvider(): array
    {
        return [
            'invalid driver' => [['storage' => 'dog']],
            'null driver' => [['storage' => null]],
            'missing driver key' => [[]],
        ];
    }

    /**
     * @covers ::message
     */
    public function testMessage(): void
    {
        $rule = $this->rule(true);

        $this->assertStringContainsString('not uploaded', $rule->message());
    }

    public function rule(bool $shouldExist): FileExists
    {
        $driver = $this->createStub(StorageInterface::class);
        $driver->method('findObject')->willReturn($shouldExist);

        return new FileExists($driver);
    }
}
