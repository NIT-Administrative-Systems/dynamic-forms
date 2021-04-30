<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Rules;

use Northwestern\SysDev\DynamicForms\Errors\UnknownStorageDriverError;
use Northwestern\SysDev\DynamicForms\Rules\FileExistsInS3;
use Northwestern\SysDev\DynamicForms\Storage\StorageInterface;
use Orchestra\Testbench\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Rules\FileExistsInS3
 */
class FileExistsInS3Test extends TestCase
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

        $this->assertEquals($passes, $rule->passes('test', $file));
    }

    public function passesProvider(): array
    {
        $valid = [
            'name' => 'foo1',
            'key' => 'foo1', // should match name
            'url' => '/dynamic-forms/storage/s3/foo1', // should match name
            'storage' => FileExistsInS3::STORAGE_S3,
        ];

        return [
            // file, should exist in storage, passes
            'valid' => [$valid, true, true],
            'missing field' => [['storage' => FileExistsInS3::STORAGE_S3], true, false],
            'unexpected url' => [array_merge($valid, ['url' => '/dog']), true, false],
            'not consistent' => [array_merge($valid, ['name' => 'dog']), true, false],
            'file does not exist' => [$valid, false, false],
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

    public function rule(bool $shouldExist): FileExistsInS3
    {
        $driver = $this->createStub(StorageInterface::class);
        $driver->method('findObject')->willReturn($shouldExist);

        return new FileExistsInS3($driver);
    }
}
