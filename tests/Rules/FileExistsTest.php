<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Rules;

use Northwestern\SysDev\DynamicForms\Rules\FileExists;
use Northwestern\SysDev\DynamicForms\Storage\FileDriver;
use Northwestern\SysDev\DynamicForms\Storage\S3Driver;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Rules\FileExists
 */
final class FileExistsTest extends TestCase
{
    #[DataProvider('passesProvider')]
    public function testPasses(array $file, bool $shouldExist, bool $passes): void
    {
        $driver = $file['storage'] == S3Driver::STORAGE_S3 ? S3Driver::class : FileDriver::class;
        $rule = $this->rule($driver, $shouldExist);

        $this->app['router']
            ->get('/dynamic-forms/storage/s3/{fileKey}')
            ->name('dynamic-forms.S3-file-redirect');

        $this->app['router']
            ->get('/dynamic-forms/storage/url/')
            ->name('dynamic-forms.url-file-download');

        $this->assertEquals($passes, $rule->passes('test', $file));
    }

    public static function passesProvider(): array
    {
        $validS3 = [
            'name' => 'foo1',
            'key' => 'foo1', // should match name
            'url' => 'http://localhost/dynamic-forms/storage/s3/foo1', // should match name
            'storage' => S3Driver::STORAGE_S3,
        ];

        $validS3WithPath = array_merge($validS3, [
            'key' => 'folder/foo1',
            'url' => 'http://localhost/dynamic-forms/storage/s3/folder/foo1',
        ]);

        $validURL = [
            'name' => 'foo1',
            'url' => 'http://localhost/dynamic-forms/storage/url?baseUrl=https%3A%2F%2Fapi.form.io&project=&form=/foo1', // should match name with additional data fields added on
            'storage' => FileDriver::STORAGE_URL,
            'data' => ['baseUrl' => 'https://api.form.io',
                'project' => '',
                'form' => '', ],
        ];

        return [
            // file, should exist in storage, passes
            'valid S3' => [$validS3, true, true],
            'missing field S3' => [['storage' => S3Driver::STORAGE_S3], true, false],
            'unexpected url S3' => [array_merge($validS3, ['url' => '/dog']), true, false],
            'file does not exist S3' => [$validS3, false, false],
            'in s3 with a subfolder' => [$validS3WithPath, true, true],

            // file, should exist in storage, passes
            'valid URL' => [$validURL, true, true],
            'missing field URL' => [['storage' => FileDriver::STORAGE_URL], true, false],
            'unexpected url URL' => [array_merge($validURL, ['url' => '/dog']), true, false],
            'file does not exist URL' => [$validURL, false, false],
        ];
    }

    /**
     * @covers ::message
     */
    public function testMessage(): void
    {
        $rule = $this->rule(S3Driver::class, true);

        $this->assertStringContainsString('not uploaded', $rule->message());
    }

    public function rule($driver, bool $shouldExist): FileExists
    {
        $driver = $this->createPartialMock($driver, ['findObject']);

        $driver->method('findObject')->willReturn($shouldExist);

        return new FileExists($driver);
    }
}
